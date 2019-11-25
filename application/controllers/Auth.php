<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller
{
	function __construct()
	{
		parent::__construct();


        $this->lang->load(array('auth', 'tank_auth'));
        $this->load->model(array('user_model','member_model'));
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		//$this->load->library('security');
        $this->load->library('tank_auth');
		$this->load->helper('security');

	    $this->load->library('layout', 'layouts/auth_layout');
        $this->layout->setLayout("layouts/auth_layout");
	}

	function index()
	{
		if ($message = $this->session->flashdata('message')) {
			$this->load->view('auth/general_message', array('message' => $message));
		} else {
			redirect('/auth/login');
		}
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{

		if ($this->tank_auth->is_logged_in()) {			// logged in
            if($this->session->userdata('login_before')!=null){
                redirect($this->session->userdata('login_before'),'refresh');
            }else{
                redirect('');
            }

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {
		    if(isset($_SERVER['HTTP_REFERER'])){
                $this->session->set_userdata('referred_from', $_SERVER['HTTP_REFERER']);
		    }
			$data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
					$this->config->item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

			$this->form_validation->set_rules('login',  $this->lang->line('username'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('password',  $this->lang->line('password'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			$data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
			if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
				if ($data['use_recaptcha'])
					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
				else
					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
			}
			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'),
						$data['login_by_username'],
						$data['login_by_email'])) {								// success
					//redirect('');
                    redirect($this->session->userdata('referred_from'),"refresh");

				} else {
					$errors = $this->tank_auth->get_error_message();
					if (isset($errors['banned'])) {								// banned user
						$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);

					} elseif (isset($errors['not_activated'])) {				// not activated user
						redirect('/auth/send_again/');

					} else {													// fail
						foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					}
				}
			}
			$data['show_captcha'] = FALSE;
			$this->layout->view('auth/login_form', $data);
		}
	}
    function login_by_id()
    {
        if ($this->tank_auth->is_logged_in()) {									// logged in
            redirect('');

        } elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
            redirect('/auth/send_again/');

        } else {
            $data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
                $this->config->item('use_username', 'tank_auth'));
            $data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

            $this->form_validation->set_rules('login',  $this->lang->line('username'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('password',  $this->lang->line('password'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('remember', 'Remember me', 'integer');

            // Get login for counting attempts to login
            if ($this->config->item('login_count_attempts', 'tank_auth') AND
                ($login = $this->input->post('login'))) {
                $login = $this->security->xss_clean($login);
            } else {
                $login = '';
            }

            $data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
            if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
                if ($data['use_recaptcha'])
                    $this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
                else
                    $this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
            }
            $data['errors'] = array();

            if ($this->form_validation->run()) {								// validation ok
                if ($this->tank_auth->login(
                    $this->form_validation->set_value('login'),
                    $this->form_validation->set_value('password'),
                    $this->form_validation->set_value('remember'),
                    $data['login_by_username'],
                    $data['login_by_email'])) {								// success
                    redirect('');

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    if (isset($errors['banned'])) {								// banned user
                        $this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);

                    } elseif (isset($errors['not_activated'])) {				// not activated user
                        redirect('/auth/send_again/');

                    } else {													// fail
                        foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                    }
                }
            }
            $data['show_captcha'] = FALSE;
            $this->layout->view('auth/login_id_form', $data);
        }
    }

	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->tank_auth->logout();

		$this->_show_message($this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} elseif (!$this->config->item('allow_registration', 'tank_auth')) {	// registration is off
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));

		} else {
			$use_username = $this->config->item('use_username', 'tank_auth');
			if ($use_username) {
				$this->form_validation->set_rules('username',  $this->lang->line('username'), 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
			}
            $this->form_validation->set_rules('realname',  $this->lang->line('realname'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('email',  $this->lang->line('email'), 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
			$this->form_validation->set_rules('confirm_password',  $this->lang->line('confirm_password'), 'trim|required|xss_clean|matches[password]');

			$captcha_registration	= $this->config->item('captcha_registration', 'tank_auth');
			$use_recaptcha			= $this->config->item('use_recaptcha', 'tank_auth');
			if ($captcha_registration) {
				if ($use_recaptcha) {
					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
				}
			}
			$data['errors'] = array();

			$email_activation = $this->config->item('email_activation', 'tank_auth');

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->create_user(
						$use_username ? $this->form_validation->set_value('username') : '',
                        $this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'),
						$email_activation,
                    $this->form_validation->set_value('realname')))) {									// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					if ($email_activation) {									// send "activate" email
						$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

						$this->_send_email('activate', $data['email'], $data);

						unset($data['password']); // Clear password (just for any case)

						$this->_show_message($this->lang->line('auth_message_registration_completed_1'));

					} else {
						if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email

							//$this->_send_email('welcome', $data['email'], $data);
						}
						unset($data['password']); // Clear password (just for any case)

                        alert('가입이 완료되었습니다. 로그인 화면으로 이동합니다.','/auth/login');
					}
				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			if ($captcha_registration) {
				if ($use_recaptcha) {
					$data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$data['captcha_html'] = $this->_create_captcha();
				}
			}
			$data['use_username'] = $use_username;
			$data['captcha_registration'] = $captcha_registration;
			$data['use_recaptcha'] = $use_recaptcha;
			$this->layout->view('auth/register_form', $data);
		}
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function send_again()
	{
		if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
			redirect('/auth/login');

		} else {
			$this->form_validation->set_rules('email',  $this->lang->line('email'), 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->change_email(
						$this->form_validation->set_value('email')))) {			// success

					$data['site_name']	= $this->config->item('website_name', 'tank_auth');
					$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

					$this->_send_email('activate', $data['email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/send_again_form', $data);
		}
	}

    function naver_login(){

        //받은 정보 이메일, 아이디 받아서 처리하는거..
        $this->layout->setLayout("layouts/auth_layout");
        $this->layout->view('auth/naver_login');
    }
    function google_login(){

        //받은 정보 이메일, 아이디 받아서 처리하는거..
        $this->layout->setLayout("layouts/auth_layout");
        $this->layout->view('auth/google_login');
    }


    function sns_login_check(){

        $sns_id = $this->input->post('sns_id');
        $sns_email = $this->input->post('sns_email');
        $sns_type = $this->input->post('sns_type');
        $remember = $this->input->post('remember');
        $unique_id = $this->input->post('unique_id');
        $result = $this-> user_model ->check_sns_user($sns_email,$sns_type); // user table 에서 해당 아이디로 있는지 없는지 확인

        //$result==1이면 가입 된건데, sns_profile에도 있는지 확인..
        $type = 'default';
        if($result==1){ //가입돼있으면
            //user_profile에 대해서..
            $this->tank_auth->sns_login($sns_email, $remember, $sns_type,$unique_id);
            $type = 'login';

        }else{ //가입 안돼있으면
            //해당 아이디, 비밀번호로 가입시키기
            // users 에 추가하기
            $email_activation=1;
            //create_sns_user($username, $email, $email_activation, $sns_type)
            $this->tank_auth->create_sns_user(
                $sns_id,
                $sns_email,
                $email_activation,
                $sns_type,
                $unique_id);

            //sns_login table에 만들기
            //회원가입 완료 페이지로 이동 -> 하기위해 json 으로 보내기..
            //로그인하기

            $remember = 0;
            $this->tank_auth->sns_login($sns_email, $remember, $sns_type,$unique_id);
            $type = 'regi';

        }
        echo $type;

    }

    //이메일 중복있는지 확인
    function check_email_dup(){
        $email = $this->input->post('email');
        $result = $this-> user_model ->check_email($email); // user table 에서 해당 아이디로 있는지 없는지 확인

        echo $result;
    }


    //팀 멤버 지정시
    function find_gen_user($team_id){

        $email = $this->input->post('email');
        $result = $this-> user_model ->check_gen_email($email); // user table 에서 해당 아이디로 있는지 없는지 확인 + 레벨이 5가 아닌 (모임장이 아닌) 사람들

        //이미 지정되어있는지 확인하기 .
        if($result!=0){ //지정할 수 있는 상태인데
            $dup_result = $this->member_model->check_dup_member($result,$team_id);
            if($dup_result>0){ //이미 있는경우에
                $result = -1;
            }
        }

        echo $result;
    }

    function kakao_login(){

        //받은 정보 이메일, 아이디 받아서 처리하는거..
        $this->layout->setLayout("layouts/auth_layout");
        $this->layout->view('auth/kakao_login');
    }

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Activate user
		if ($this->tank_auth->activate_user($user_id, $new_email_key)) {		// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgot_password()
	{

		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {
			$this->form_validation->set_rules('login',  $this->lang->line('email'), 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('login')))) {

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with password activation link
					$this->_send_email('forgot_password', $data['email'], $data);
					alert('입력하신 이메일인 '.$data['email'].'로 링크를 전송하였습니다. 이메일이 오지 않았을 경우 스팸메일함을 확인해주세요.','/auth/login');

					$this->_show_message($this->lang->line('auth_message_new_password_sent'));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->layout->view('auth/forgot_password_form', $data);
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password()
	{
		$user_id		= $this->uri->segment(3);
		$new_pass_key	= $this->uri->segment(4);

		$this->form_validation->set_rules('new_password', '새 비밀번호', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
		$this->form_validation->set_rules('confirm_new_password', '비밀번호 확인',  'trim|required|xss_clean|matches[new_password]');

		$data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($data = $this->tank_auth->reset_password(
					$user_id, $new_pass_key,
					$this->form_validation->set_value('new_password')))) {	// success

				$data['site_name'] = $this->config->item('website_name', 'tank_auth');

				// Send email with new password
				//$this->_send_email('reset_password', $data['email'], $data); //이메일 보내기 안함
                alert('비밀번호 설정이 완료되었습니다.','/auth/login');

				//$this->_show_message($this->lang->line('auth_message_new_password_activated').' '.anchor('/auth/login/', 'Login'));

			} else {														// fail
                alert('비밀번호 설정을 실패했습니다. 이미 설정했을 수 있습니다. 설정한 적이 없다면 다시 비밀번호 찾기 메뉴에서 재설정 이메일을 보내주세요.');
				//$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		} else {
			// Try to activate user by password key (if not activated yet)
			/*if ($this->config->item('email_activation', 'tank_auth')) {
				$this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}*/
		}
		$this->layout->view('auth/reset_password_form', $data);
	}

	/**
	 * Change user password
	 *
	 * @return void
	 */
	function change_password()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login');

		} else {
            $status = $this->data['status'];
            $user_id = $this->data['user_id'];
            $level = $this->data['level'];
            $alarm_cnt = $this->data['alarm'];
            $user_data = array(
                'status' => $status,
                'user_id' => $user_id,
                'username' =>$this->data['username'],
                'level' =>$level,
                'alarm' =>$alarm_cnt
            );
			$this->form_validation->set_rules('old_password', '현재 비밀번호', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', '새 비밀번호', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
			$this->form_validation->set_rules('confirm_new_password', '비밀번호 재입력', 'trim|required|xss_clean|matches[new_password]');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success
					$this->_show_message($this->lang->line('auth_message_password_changed'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
            $this->layout->setLayout("layouts/default");
			$this->layout->view('auth/change_password_form', array('data'=>$data, 'user'=>$user_data));
		}
	}

	/**
	 * Change user email
	 *
	 * @return void
	 */

	function error(){
	    alert('에러가 발생했습니다. 관리자에게 문의하세요. Uid invalid');
    }
	function change_email()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login');

		} else {
			$this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', '새 이메일', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password')))) {			// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with new email address and its activation link
					$this->_send_email('change_email', $data['new_email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_email_form', $data);
		}
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_email()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Reset email
		if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_new_email_activated').' '.anchor('/auth/login', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_new_email_failed'));
		}
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login');

		} else {

            $status = $this->data['status'];
            $user_id = $this->data['user_id'];
            $level = $this->data['level'];
            $alarm_cnt = $this->data['alarm'];
            $sns_type = $this->user_model-> get_user_sns_type($user_id);
            $user_data = array(
                'status' => $status,
                'user_id' => $user_id,
                'username' =>$this->data['username'],
                'level' =>$level,
                'alarm' =>$alarm_cnt,
                'sns_type'=>$sns_type,
            );
		    //이 페이지에서 사용자의 상태 demand, product, import_product 있는지 확인한다..
            // 모든 상품 중 1개라도 팔린게 있으면 안됨
            /*product*/
            $prod_count = $this -> Prod_model->load_prod_by_user_id($user_id,'','','count'); // 게시물 전체 개수
            /*import_product*/
            $import_count =$this->Prod_model->load_import_prod_by_user_id($user_id,'','','count');// 게시물 전체 개수
            /*demand*/
            $demand_count =$this->Demand_model->load_demand_by_user_id($user_id,'','','count');// 게시물 전체 개수
            $count = array(
                'prod'=>$prod_count,
                'import'=>$import_count,
                'demand'=>$demand_count,

            );
            if(($this->input->post('user_id')!=null)&&($prod_count==0||$import_count==0||$demand_count==0)){ // 모두 0이어야한다..
               // $this->do_unregister($user_data);
                $is_sns = $this->user_model->drop_user($user_id);
                $this->tank_auth->logout();
                alert('탈퇴가 완료되었습니다. 이용해주셔서 감사합니다.','/');

            }else{ // 확인받는 곳

                $this->layout->setLayout("layouts/default");
                $this->layout->view('auth/unregister_confirm', array('user'=>$user_data, 'count'=>$count));
            }
//            if($prod_count==0&&$demand_count==0&&$import_count==0){
//                //$this->do_unregister($user_data);
//                if($this->input->post('user_id')!=null){
//                    echo 'sdf';
//                }else{ // 확인받는 곳
//
//                    $this->layout->view('auth/unregister_confirm', array('login'=>$user_data));
//                }
//            }else{
//                alert('등록한 상품이 없을 경우에만 탈퇴가 가능합니다.');
//            }

		}
	}
	function unregi_naver($user_id){

        $prod_count = $this -> Prod_model->load_prod_by_user_id($user_id,'','','count'); // 게시물 전체 개수
        $import_count =$this->Prod_model->load_import_prod_by_user_id($user_id,'','','count');// 게시물 전체 개수
        $demand_count =$this->Demand_model->load_demand_by_user_id($user_id,'','','count');// 게시물 전체 개수

        if(($user_id!=null)&&($prod_count==0||$import_count==0||$demand_count==0)){ // 모두 0이어야한다..
           $this->user_model->drop_user($user_id);

            $client_secret = "JnKYMsmJ4T";
            $client_id = "Sny1aaXz6uLFaDTDWUvO";
            $code = $_GET["code"];
            $state = $_GET["state"];

            //엑세스 토큰 주세요..

            //$apiURL = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&access_token=".$access_token";
            $apiURL = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&code=".$code."&state=".$state;

            $is_post = false;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            curl_setopt($ch, CURLOPT_POST, $is_post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $headers = array();
            $response = curl_exec ($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close ($ch);
            if($status_code == 200) {
                $res_array = json_decode($response);
                if(!isset($res_array->access_token)) alert('잘못된 접근입니다. CODE:AT01','/mypage');
                $access_token = $res_array->access_token;
                $auth_apiURL = "https://nid.naver.com/oauth2.0/token?grant_type=delete&client_id=Sny1aaXz6uLFaDTDWUvO&client_secret=JnKYMsmJ4T&access_token=".$access_token.'&service_provider=NAVER';

                $is_post = false;
                $new_ch = curl_init();
                curl_setopt($new_ch, CURLOPT_URL, $auth_apiURL);
                curl_setopt($new_ch, CURLOPT_POST, $is_post);
                curl_setopt($new_ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec ($new_ch);
                $status_code = curl_getinfo($new_ch, CURLINFO_HTTP_CODE);
                //Qecho "status_code:".$status_code."<br>";
                curl_close ($new_ch);
                if($status_code == 200) {
                    $res_array = json_decode($response);
                    if($res_array->result=='success'){

                        $this->tank_auth->logout();
                        alert('탈퇴가 완료되었습니다. 이용해주셔서 감사합니다.','/');
                    }else{
                        alert(serialize($res_array));
                    }
                }else{
                    alert('moimga 탈퇴는 완료되었지만, 네이버에서 moimga 탈퇴를 실패했습니다. 네이버로 로그인하신 후 보안 설정에서 moimga을 직접 끝어주세요.','/');
                }
            }

            //https://nid.naver.com/oauth2.0/token?grant_type=delete&client_id=CLIENT_ID&client_secret=CLIENT_SECRET&access_token=ACCESS_TOKEN
            //https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=jyvqXeaVOVmV&client_secret=527300A0_COq1_XV33cf&code=EIc5bFrl4RibFls1&state=9kgsGTfH4j7IyAkg
            //$accesstoken 만들어야함..
            //


        }else{ // 확인받는 곳

            alert('실패했습니다.');
        }

    }

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);
		redirect('/auth/');
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
			'img_url'		=> base_url().$this->config->item('captcha_path', 'tank_auth'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
			'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
			'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
			'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
			'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
			'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;

		} elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	function complete(){
	    redirect('/','refresh');
	    return true;
    }

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
