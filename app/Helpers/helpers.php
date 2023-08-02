<?php

  function get_cat($id){
    return App\Models\Category::where('parent_id', $id)->get();
  }
  
 
?>
