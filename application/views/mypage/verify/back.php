
<?php
/*
 * 특정 URL 지정
 */
//$nextURL = "http://www.danal.co.kr";

/*
 * 창 닫기 Script
 */
$nextURL = "Javascript:self.close();";
?>
<form name="BackURL" action="<?=$nextURL?>" method="post">
</form>
<script Language="Javascript">
    document.BackURL.submit();
</script>