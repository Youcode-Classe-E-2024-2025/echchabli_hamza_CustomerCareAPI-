<?php

namespace App\Http\Controllers;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="echchablihamza1@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",                                                                                                                                                     
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

abstract class Controller
{
    //
}
