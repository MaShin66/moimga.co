
<form name="Ready" action="https://wauth.teledit.com/Danal/WebAuth/Web/Start.php" method="post">
    <?php

    MakeFormInput($Res,array("RETURNCODE","RETURNMSG"));
    MakeFormInput($ByPassValue);
    ?>
</form>
<script Language="JavaScript">
    document.Ready.submit();
</script>
