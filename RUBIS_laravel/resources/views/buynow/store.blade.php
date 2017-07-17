@include('header')
<?php
if ($qty == 1)
    print("<center><h2>Your have successfully bought this item.</h2></center>\n");
else
    print("<center><h2>Your have successfully bought these items.</h2></center>\n");
?>
@include('footer')