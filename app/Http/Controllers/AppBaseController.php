<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use DB;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Api\ApiController
{

    public function sendResponse($result, $message)
    {
		$response = ResponseUtil::makeResponse($message, $result);
		$this->_get_db_queries();
		$response['queries'] = $this->data['queries'];
        return Response::json($response);
    }

    public function sendError($error, $code = 404)
    {
		$this->_get_db_queries();
		$result['data'] = $this->data['queries'];

        return Response::json(ResponseUtil::makeError($error), $code);
    }
}
