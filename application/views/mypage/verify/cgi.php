
<form name="Success" action="/mypage/verify/success" method="post">
    <?php
    MakeFormInput($_POST,array("TID"));
    MakeFormInput($Res,array("RETURNCODE","RETURNMSG"));
    ?>
</form>
<script>
    document.Success.submit();
</script>