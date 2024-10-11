<?php

include_once "../../../loader.php";
use App\Services\CityService;
use App\Utilities\Response;

$tokenJWT = getBearerToken();
$user = isValidToken($tokenJWT);

if (!$user){
    Response::respondAndDie(["Invalid jwt token"] , Response::HTTP_UNAUTHORIZED);
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestBody = json_decode(file_get_contents('php://input'),true);
$cityService = new CityService();

switch ($requestMethod) {
    case 'GET':

        $data = [
            'province_id' => isset($_GET["province_id"]) ? $_GET["province_id"] : null,
            'page' => isset($_GET["page"]) ? $_GET["page"] : null,
            'pagesize' => isset($_GET["pagesize"]) ? $_GET["pagesize"] : null,
            'fields' => isset($_GET["fields"]) ? $_GET["fields"] : '*',
            'orderby' => isset($_GET['orderby']) ? $_GET['orderby'] : null,
        ];
        if (!hasAccessToProvince($user,$data['province_id'])){
            Response::respondAndDie(['You have no access to this province'] , Response::HTTP_FORBIDDEN);
        }
        if (isset($_GET["page"]) && $data['page'] < 1) {
            Response::respondAndDie(["Can't response 0 page !"] , Response::HTTP_NOT_FOUND);
        }
        $response = $cityService->read($data);
        if (empty($response)){
            Response::respondAndDie($response , Response::HTTP_NOT_FOUND);
        }
        Response::respondAndDie($response , Response::HTTP_OK);
    case 'POST':
        if (!isValidCity($requestBody)){
            Response::respondAndDie(['Invalid City Data'] , Response::HTTP_NOT_ACCEPTABLE);
        }
        $response = $cityService->create($requestBody);
        Response::respondAndDie($response , Response::HTTP_CREATED);
    case 'PUT':

        $cityId = $requestBody["city_id"];
        $cityName = $requestBody["city_name"];
        if (!is_numeric($cityId) or empty($cityName)){
            Response::respondAndDie(['Invalid City Data'] , Response::HTTP_NOT_ACCEPTABLE);
        }
        $response = $cityService->update($cityId, $cityName);
        Response::respondAndDie($response , Response::HTTP_OK);
    case 'DELETE':
        $cityId = isset($_GET["city_id"]) ? $_GET["city_id"] : null;
        $response = $cityService->delete($cityId);
        Response::respondAndDie($response , Response::HTTP_OK);
    default:
        Response::respondAndDie(['Invalid Method'] , Response::HTTP_METHOD_NOT_ALLOWED);
}
