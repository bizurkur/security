<?php

namespace Bitty\Security\Shield;

use Bitty\Http\Response;
use Bitty\Security\Shield\AbstractShield;
use Bitty\Security\User\UserInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpBasicShield extends AbstractShield
{
    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ?ResponseInterface
    {
        $match = $this->context->getPatternMatch($request);
        if (empty($match) || empty($match['roles'])) {
            return null;
        }

        $user = $this->getUser($request);
        if ($user) {
            $this->authorize($user, $match['roles']);

            return null;
        }

        $headers = [
            'WWW-Authenticate' => sprintf(
                'Basic realm="%s"',
                $this->config['realm']
            ),
        ];

        return new Response('', 401, $headers);
    }

    /**
     * Gets the authenticated user, if any.
     *
     * @param ServerRequestInterface $request
     *
     * @return UserInterface|null
     */
    protected function getUser(ServerRequestInterface $request): ?UserInterface
    {
        $user = $this->context->get('user');
        if ($user) {
            $user = $this->authenticator->reloadUser($user);
            if ($user) {
                return $user;
            }
        }

        $params   = $request->getServerParams();
        $username = empty($params['PHP_AUTH_USER']) ? null : $params['PHP_AUTH_USER'];
        $password = empty($params['PHP_AUTH_PW']) ? null : $params['PHP_AUTH_PW'];

        if (empty($username) || empty($password)) {
            return null;
        }

        $user = $this->authenticate($username, $password);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultConfig(): array
    {
        return [
            'realm' => 'Secured Area',
        ];
    }
}
