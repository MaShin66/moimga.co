<?php

// Errors
$lang['auth_incorrect_password'] = '비밀번호가 정확하지 않습니다.';
$lang['auth_incorrect_login'] = '이메일이 정확하지 않습니다.';
$lang['auth_sns_login'] = ' SNS로 가입하신 경우 SNS 로그인만 지원합니다.';
$lang['auth_sns_login_exist'] = '이미 이메일로 가입한 적이 있습니다. 이메일로 로그인 해주세요.';
$lang['auth_incorrect_email_or_username'] = '입력하신 아이디 혹은 이메일이 존재하지 않습니다.';
$lang['auth_email_in_use'] = '입력하신 이메일이 이미 사용중입니다. 다른 이메일을 사용해주세요.';
$lang['auth_username_in_use'] = '입력하신 아이디는 이미 사용중입니다. 다른 아이디를 사용해주세요.';
$lang['auth_current_email'] = '입력하신 이메일은 회원님의 현재 이메일입니다.';
$lang['auth_incorrect_captcha'] = 'Your confirmation code does not match the one in the image.';
$lang['auth_captcha_expired'] = 'Your confirmation code has expired. Please try again.';

// Notifications
$lang['auth_message_logged_out'] = 'You have been successfully logged out.';
$lang['auth_message_registration_disabled'] = 'Registration is disabled.';
$lang['auth_message_registration_completed_1'] = '회원가입이 완료되었습니다. 입력하신 이메일인 %s의 메일함을 확인해주세요. 이메일이 전송되지 않았다면 스팸메일함도 확인해주세요.';
$lang['auth_message_registration_completed_2'] = '회원가입이 완료되었습니다.';
$lang['auth_message_activation_email_sent'] = '새로운 인증 코드가 %s로 전송되었습니다. 이메일 내용을 확인 후 그대로 진행해주세요. 이메일이 전송되지 않았다면 스팸메일함도 확인해주세요.';
$lang['auth_message_activation_completed'] = 'Your account has been successfully activated.';
$lang['auth_message_activation_failed'] = '입력하신 코드가 맞지 않거나 시간이 만료되었습니다. 로그인 화면에서 아이디를 입력하시면 재발송이 가능합니다.';
$lang['auth_message_password_changed'] = '비밀번호가 변경되었습니다.';
$lang['auth_message_new_password_sent'] = '새 비밀번호와 관련된 이메일을 보냈습니다. 이메일 내용을 확인 후 그대로 진행해주세요. 이메일이 전송되지 않았다면 스팸메일함도 확인해주세요.';
$lang['auth_message_new_password_activated'] = '비밀번호 재설정이 완료되었습니다.';
$lang['auth_message_new_password_failed'] = 'Your activation key is incorrect or expired. Please check your email again and follow the instructions.';
$lang['auth_message_new_email_sent'] = '이메일 변경 관련 이메일을 %s로 전송했습니다. 이메일 내용을 확인 후 그대로 진행해주세요. 이메일이 전송되지 않았다면 스팸메일함도 확인해주세요.';
$lang['auth_message_new_email_activated'] = 'You have successfully changed your email';
$lang['auth_message_new_email_failed'] = 'Your activation key is incorrect or expired. Please check your email again and follow the instructions.';
$lang['auth_message_banned'] = 'You are banned.';
$lang['auth_message_unregistered'] = 'Your account has been deleted...';

// Email subjects
$lang['auth_subject_welcome'] = '[%s] 잡스런공간에 가입해주셔서 감사합니다.';
$lang['auth_subject_activate'] = '[%s] 회원 가입 인증 메일';
$lang['auth_subject_forgot_password'] = '[%s] 비밀번호 찾기 메일';
$lang['auth_subject_reset_password'] = '[%s] 비밀번호 재설정 메일';
$lang['auth_subject_change_email'] = '[%s] 이메일 변경 안내 메일';

/* End of file tank_auth_lang.php */
/* Location: ./application/language/english/tank_auth_lang.php */