<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Entity\User;
use App\Exception\UserException;
use App\Service\User\Find;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class GetUserMiddleware
 * @package App\Middleware
 */
class GetUserMiddleware
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Find
     */
    private function getFindUserService(): Find
    {
        return $this->container->get('find_user_service');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     * @throws UserException
     */
    public function __invoke(Request $request,Response $response, callable $next): Response
    {
        $route = $request->getAttribute('route');
        $userId = (int)$route->getArgument('id');

        $user = $this->getFindUserService()->getOne($userId);

        $userObject = new User();
        $userObject->setId($user->id);
        $userObject->setEmail($user->email);
        $userObject->setName($user->name);
        $userObject->setPhone($user->phone);
        $userObject->setAddress($user->address);

        return $next($request->withAttribute('user', $userObject), $response);
    }
}
