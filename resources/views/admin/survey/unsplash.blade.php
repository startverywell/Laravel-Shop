<?php
    $div1 = "<div class='image-column'>";
    $div2 = "<div class='image-column'>";
    $flag = true;
    foreach ($photo as $key => $value){
        if($flag){
            $div1 .= '
            <div class="image-container" data-zoom=0 data-left=0>
                <img loading="lazy" src="'.$value->urls->small.'" alt="'.$value->alt_description.'" data-download="'.$value->links->download_location.'?client_id='.env('UNSPLASH_ACCESS_KEY').'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                <div class="image-overlay">
                    <a target="_blank" href="'.$value->links->html.'?utm_source=Formstylee&utm_medium=referral">'.$value->user->name.'</a>
                </div>    
            </div>
            ';
            $flag = false;
        } else {
            $div2 .= '
            <div class="image-container" data-zoom=0 data-left=1>
                <img loading="lazy" src="'.$value->urls->small.'" alt="'.$value->alt_description.'" data-download="'.$value->links->download_location.'?client_id='.env('UNSPLASH_ACCESS_KEY').'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                <div class="image-overlay">
                    <a target="_blank" href="'.$value->links->html.'?utm_source=Formstylee&utm_medium=referral">'.$value->user->name.'</a>
                </div>    
            </div>
            ';
            $flag = true;
        }
    }
    $div1 .= '</div>';
    $div2 .= '</div>';
    echo $div1;
    echo $div2;
?>