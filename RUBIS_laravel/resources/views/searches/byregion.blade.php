@include('header')
<?php print("<h2>Items in category $CatName</h2><br><br>"); ?>
@if(count($items) == 0 and $items->currentPage()!=1)
    <?php print("<h3>Sorry, but there is no item in this category for this region.</h3><br>\n"); ?>
@elseif(count($items) == 0 and $items->currentPage()!=1)
    <?php
    print("<h2>Sorry, but there are no more items available in this category for this region!</h2>");
    print("<p><CENTER>\n<a href=\"/PHP/SearchItemsByRegion.php?category=$CatID&region=$RegionID".
        "&categoryName=".urlencode($CatName)."&page=".($items->currentPage()-1)."&nbOfItems=".$nbOfItems."\">Previous page</a>\n</CENTER>\n");
    ?>
@else
    <?php
    print("<TABLE border=\"1\" summary=\"List of items\">".
        "<THEAD>".
        "<TR><TH>Designation<TH>Price<TH>Bids<TH>End Date<TH>Bid Now".
        "<TBODY>"); ?>
    @foreach($items as $item)
        @if($item->max_bid == 0 or $item->max_bid == NULL)
            <?php $item->max_bid = $item->initial_price; ?>
        @endif
        <?php
        print("<TR><TD><a href=\"/PHP/ViewItem.php?itemId=".$item->id."\">".$item->name.
            "<TD>".$item->max_bid.
            "<TD>".$item->nb_of_bids.
            "<TD>".$item->end_date.
            "<TD><a href=\"/PHP/PutBidAuth.php?itemId=".$item->id."\"><IMG SRC=\"".asset('storage/bid_now.jpg')."\" height=22 width=90></a>");
        ?>
    @endforeach
    <?php
    print("</TABLE>");
    if ($items->currentPage() == 1) {
        print("<p><CENTER>\n<a href=\"/PHP/SearchItemsByCategory.php?category=$CatID".
            "&categoryName=".urlencode($CatName)."&page=".($items->currentPage() + 1)."&nbOfItems=".$nbOfItems."\">Next page</a>\n</CENTER>\n");
    } else {
        print("<p><CENTER>\n<a href=\"/PHP/SearchItemsByCategory.php?category=$CatID".
            "&categoryName=".urlencode($CatName)."&page=".($items->currentPage() - 1)."&nbOfItems=".$nbOfItems."\">Previous page</a>\n&nbsp&nbsp&nbsp".
            "<a href=\"/PHP/SearchItemsByCategory.php?category=$CatID".
            "&categoryName=".urlencode($CatName)."&page=".($items->currentPage() + 1)."&nbOfItems=".$nbOfItems."\">Next page</a>\n\n</CENTER>\n");
    }
    ?>
@endif
@include('footer')
