<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Exception\UserException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CreateController
 * @package App\Controller\User
 */
class CreateController extends AbstractUserController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws UserException
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $params = (array) $request->getParsedBody();

        $this->validateParams($params);

        $user = $this->getCreateUserService()->create($params);

        return $this->jsonResponse($response, 'success', $user, 201);
    }

    /**
     * @param array $params
     * @throws UserException
     */
    private function validateParams(array $params): void
    {
        if (!isset($params['name'])) {
            throw new UserException('The field "name" is required.', 400);
        }

        if (!isset($params['email'])) {
            throw new UserException('The field "email" is required.', 400);
        }

        if (!isset($params['password'])) {
            throw new UserException('The field "password" is required.', 400);
        }
    }
}
