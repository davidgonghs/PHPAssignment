<?php
if($errInfor != ""){
    $html=<<<A
        <div class="alert alert-warning alert-danger fade show" role="alert">
            {$errInfor}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
A;
    echo $html;
}
?>