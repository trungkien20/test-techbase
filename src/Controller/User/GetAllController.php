<?php
declare(strict_types=1);

namespace App\Controller\User;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class GetAllController
 * @package App\Controller\User
 */
class GetAllController extends AbstractUserController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $page = $request->getQueryParam('page', null);
        $perPage = $request->getQueryParam('limit', null);
        $name = $request->getQueryParam('name', null);
        $email = $request->getQueryParam('email', null);

        $users = $this->getFindUserService()->getUsersByPage((int) $page, (int) $perPage, $name, $email);

        return $this->jsonResponse($response, 'success', $users, 200);
    }
}
