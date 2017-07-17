@include('header')
<br>&nbsp;
<center>
    <h2>
        Buy this item !</h2></center>

<center>
    <p><br><font color="#FF0000">If you don't have an account on RUBiS, you
            first have to <a href="/PHP_HTML/register.html">register</a>.</font></center>

<center><table>
        <form action="/PHP/BuyNow.php" method=POST>
<?php print("<input type=hidden name=\"itemId\" value=\"$itemID\">"); ?>
    <tr>
        <td>Your nick name:</td>

        <td><input type=text size=20 name=nickname></td>
    </tr>

    <tr>
        <td>Your password:</td>

        <td><input type=password size=20 name=password></td>
    </tr>
    </table></center>

<center>
    <p><input type=submit value="Log In!"></center>
<p>
@include('footer')
