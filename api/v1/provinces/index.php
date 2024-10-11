<?php

include_once "../../../loader.php";
use App\Services\ProvinceService;
use App\Utilities\Response;

$headers = getallheaders();
if (isset($_SERVER['HTTP_AUTHORIZATION']) or isset($headers['Authorization'])) {

} else {
    // not use JWT token
    Response::respondAndDie(["You have to use JWT token"] , Response::HTTP_UNAUTHORIZED);
}



$tokenJWT = getBearerToken();

$user = isValidToken($tokenJWT);


if (!$user){
    // validation correct JWT token
    Response::respondAndDie(["Invalid jwt token"] , Response::HTTP_UNAUTHORIZED);
}



$classMethods = $_SERVER["REQUEST_METHOD"];

$requestBody = json_decode(file_get_contents('php://input'),true);

$provinceService = new ProvinceService();

$method = new $classMethods();
$method->sendResponse();


class GET
{
    public function sendResponse (): void
    {
        global $provinceService, $user;
        $data = [
            'id' => $_GET["id"] ?? null,
            'page' => $_GET["page"] ?? null,
            'pagesize' => $_GET["pagesize"] ?? null,
            'fields' => $_GET["fields"] ?? '*',
            'orderby' => $_GET['orderby'] ?? null,
        ];
        if (!hasAccessToProvince($user,$data['id'])){
            Response::respondAndDie(['You have no access to this province'] , Response::HTTP_FORBIDDEN);
        }
        if (isset($_GET["page"]) && $data['page'] < 1) {
            Response::respondAndDie(["Can't response 0 page !"] , Response::HTTP_NOT_FOUND);
        }
        $response = $provinceService->read($data);
        if (empty($response)){
            Response::respondAndDie($response , Response::HTTP_NOT_FOUND);
        }
        Response::respondAndDie($response , Response::HTTP_OK);

    }


}
class POST
{

    public function sendResponse (): void
    {
        global $requestBody,$provinceService;
        if (!isValidProvince($requestBody)){
            Response::respondAndDie(['Invalid Province Data'] , Response::HTTP_NOT_ACCEPTABLE);
        }
        $response = $provinceService->create($requestBody);
        Response::respondAndDie($response , Response::HTTP_CREATED);
    }
}
class PUT
{

    public function sendResponse ()
    {

        global $requestBody, $provinceService;
        $id = $requestBody["id"];
        $name = $requestBody["name"];
        if (!is_numeric($id) or empty($name)){
            Response::respondAndDie(['Invalid Province Data'] , Response::HTTP_NOT_ACCEPTABLE);
        }
        $response = $provinceService->update($id, $name);
        Response::respondAndDie($response , Response::HTTP_OK);
    }
}
class DELETE
{

    public function sendResponse ()
    {
        global $provinceService;
        $id = isset($_GET["id"]) ? $_GET["id"] : null;
        $response = $provinceService->delete($id);
        Response::respondAndDie($response , Response::HTTP_OK);
    }
}

