<?php
    $div1 = "<div class='image-column'>";
    $div2 = "<div class='image-column'>";
    foreach ($photo as $key => $value){
        $div1 .= '
        <div class="image-container" data-zoom=0 data-left=0>
            <img loading="lazy" src="'.$value->urls->small.'" alt="'.$value->alt_description.'" data-download="'.$value->links->download_location.'?client_id='.env('UNSPLASH_ACCESS_KEY').'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
            <div class="image-overlay">
                <a target="_blank" href="'.$value->links->html.'?utm_source=Formstylee&utm_medium=referral">'.$value->user->name.'</a>
            </div>    
        </div>
        ';
    }
    foreach ($pixa_photo as $key => $value){
        $div2 .= '
        <div class="image-container" data-zoom=0 data-left=1>
            <img loading="lazy" src="'.$value->imageURL.'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
            <div class="image-overlay">
                <a target="_blank" href="'.$value->pageURL.'">'.$value->user.'</a>
            </div>    
        </div>
        ';
    }
    $div1 .= '</div>';
    $div2 .= '</div>';
    echo $div1;
    echo $div2;
?>