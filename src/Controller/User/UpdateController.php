<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Exception\UserException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class UpdateController
 * @package App\Controller\User
 */
class UpdateController extends AbstractUserController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws UserException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        /** @var User $user */
        $user = $request->getAttribute('user');
        $params = (array) $request->getParsedBody();

        $userIdLogged = $this->getAndValidateUserId($params);
        $this->checkUserPermissions($user->getId(), $userIdLogged);

        $user = $this->getUpdateUserService()->update($params, $user);

        return $this->jsonResponse($response, 'success', $user, 200);
    }
}
