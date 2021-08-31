<?php
function findsubstrInStrArray($substring, $array){
    foreach ($array as $item){
        if (strpos($item,$substring)!== false){
            return true;
        } // if
    } // foreach
    return false;       
} // findsubstrInStrArray

function isActiveRoute( array $names, $output = 'active')
{
    if (in_array(Route::currentRouteName(), $names)) {
        return $output;
    } else if (findsubstrInStrArray( '/', $names )) {  // if using url and not route name ( ex. member.new ) to find
        // for parent menu or button that has multipul result pages, set active when any child is active
        foreach( $names as $urlName ) {
            if( findsubstrInStrArray( $urlName, [Request::path()]) ) {
                return $output;
            } // if
        } // foreach

        return null;
    } // if else
    else {
        return null;
    } // else
}
