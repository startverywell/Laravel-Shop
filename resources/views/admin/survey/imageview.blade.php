<div id="All" class="tabcontent active">
    <div class="card example-1 square scrollbar-dusty-grass square thin" id="card_image_view_all">
        <div class="images-wrapper" id="card_image_view_all_view">
            @foreach ($photo as $key => $value)
                <img loading="lazy" src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
            @endforeach
            @foreach ($pixa_photo as $key => $value)
                <img loading="lazy" src="{{$value->imageURL}}" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
            @endforeach
        </div>
    </div>
</div>
<div id="Unsplash" class="tabcontent">
    <div class="card example-1 square scrollbar-dusty-grass square thin" id="card_image_view_unsplash">
        <div class="images-wrapper" id="card_image_view_unsplash_view">
            @foreach ($photo as $key => $value)
                <img loading="lazy" src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
            @endforeach
        </div>
    </div> 
</div>
<div id="Pixabay" class="tabcontent">
    <div class="card example-1 square scrollbar-dusty-grass square thin" id="card_image_view_pixabay">
        <div class="images-wrapper" id="card_image_view_pixabay_view">
            @foreach ($pixa_photo as $key => $value)
                <img loading="lazy" src="{{$value->imageURL}}" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
            @endforeach
        </div>
    </div>
</div>