<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;

class Functions extends Controller
{
    function colorsClass()
    {
        return [
            "bg-primary",
            "bg-secondary",
            "bg-success",
            "bg-danger",
            "bg-warning",
            "bg-info",
            "bg-dark",
            "bg-black",
            "bg-gray",
            "bg-blue",
            "bg-navy",
            "bg-teal",
            "bg-green",
            "bg-olive",
            "bg-lime",
            "bg-yellow",
            "bg-orange",
            "bg-red",
            "bg-fuchsia",
            "bg-purple",
            "bg-maroon",

            "bg-gradient-primary",
            "bg-gradient-secondary",
            "bg-gradient-success",
            "bg-gradient-danger",
            "bg-gradient-warning",
            "bg-gradient-info",
            "bg-gradient-dark",
            "bg-gradient-gray",
            "bg-gradient-blue",
            "bg-gradient-navy",
            "bg-gradient-teal",
            "bg-gradient-green",
            "bg-gradient-olive",
            "bg-gradient-lime",
            "bg-gradient-yellow",
            "bg-gradient-orange",
            "bg-gradient-red",
            "bg-gradient-fuchsia",
            "bg-gradient-purple",
            "bg-gradient-maroon",
        ];
    }

    function dateActual(){
        return date('Y-m-d');
    }
}
