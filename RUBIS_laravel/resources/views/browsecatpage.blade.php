@include('header')
@if ( $code == 1)
    <h2>Currently available categories</h2><br>
    @foreach($catList as $cat)
        <?php print("<a href=\"/PHP/SellItemForm.php?category=".$cat->id."&user=".$userID."\">".$cat->name."</a><br>\n"); ?>
    @endforeach
@elseif($code == 2)
    <h2>Currently available categories</h2><br>
    @foreach($catList as $cat)
        <?php print("<a href=\"/PHP/SearchItemsByRegion.php?category=".$cat->id."&categoryName=".urlencode($cat->name)."&region=$regionID\">".$cat->name."</a><br>\n"); ?>
    @endforeach
@else
    <h2>Currently available categories</h2><br>
    @foreach($catList as $cat)
    <?php print("<a href=\"/PHP/SearchItemsByCategory.php?category=".$cat->id."&categoryName=".urlencode($cat->name)."\">".$cat->name."</a><br>\n"); ?>
    @endforeach
@endif
@include('footer')
