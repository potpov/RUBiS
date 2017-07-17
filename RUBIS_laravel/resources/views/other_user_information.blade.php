@include('header')
<?php
print("<h2>Information about ".$user->nickname."<br></h2>");
print("Real life name : ".$user->firstname." ".$user->lastname."<br>");
print("Email address  : ".$user->email."<br>");
print("User since     : ".$user->creation_date."<br>");
print("Current rating : <b>".$user->rating."</b><br>");
if(count($comments)==0)
    print("<h2>There is no comment for this user.</h2><br>\n");
else{
    print("<p><DL>\n"); ?>
    @foreach($comments as $comment)
        <?php print("<DT><b><BIG><a href=\"/PHP/ViewUserInfo.php?userId=".$comment->from_user_id."\">".$comment->sellerUser."</a></BIG></b>"." wrote the ".$comment->date."<DD><i>".$comment->comment."</i><p>\n"); ?>
    @endforeach
    <?php print("</DL>\n");
}
?>
@include('footer')
