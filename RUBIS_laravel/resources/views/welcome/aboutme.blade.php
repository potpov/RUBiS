<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="GENERATOR" content="Mozilla/4.76 [en] (Win95; U) [Netscape]">
    <meta name="Author" content="Julie Marguerite">
    <title>RUBiS: Log In</title>
</head>
<body text="#000000" bgcolor="#FFFFFF" link="#0000EE" vlink="#551A8B" alink="#FF0000">
&nbsp;
<center><table COLS=6 WIDTH="100%" NOSAVE >
        <tr NOSAVE>
            <td NOSAVE>
                <center><IMG SRC="{{asset('storage/RUBiS_logo.jpg')}}" height=91 width=150 align=ABSCENTER></center>
            </td>

            <td>
                <center>
                    <h2>
                        <a href="/PHP/index.html">Home</a></h2></center>
            </td>

            <td>
                <center>
                    <h2>
                        <a href="/PHP/register.html">Register</a></h2></center>
            </td>

            <td>
                <center>
                    <h2>
                        <a href="/PHP/browse.html">Browse</a></h2></center>
            </td>

            <td>
                <center>
                    <h2>
                        <a href="/PHP/sell.html">Sell</a></h2></center>
            </td>

            <td>
                <center>
                    <h2>
                        <a href="/PHP/about_me.html">About me</a></h2></center>
            </td>
        </tr>
    </table></center>

<br>&nbsp;
<center>
    <h2>
        About me</h2></center>

<center>
    <p><br><font color="#FF0000">If you don't have an account on RUBiS, you
            first have to <a href="/PHP/register.html">register</a>.</font></center>

<center><table>
        <form action="/PHP/AboutMe.php" method=POST>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
<hr WIDTH="100%">
<br><i>RUBiS (C) 2001 - Rice University/INRIA</i>
</body>
</html>