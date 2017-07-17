@include('header')<h2>Currently available regions</h2><br>@foreach($regions as $region)
<a href="/PHP/BrowseCategories.php?region={{ $region->id }}">{{ $region->name }}</a><br>
@endforeach
@include('footer')