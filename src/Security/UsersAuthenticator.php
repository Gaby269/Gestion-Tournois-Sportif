<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UsersAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait; // information importer par authentification

    public const LOGIN_ROUTE = 'app_login'; // nom de la route de connexion

    public function __construct(private UrlGeneratorInterface $urlGenerator) // générer les URL
    {
    }

    public function authenticate(Request $request): Passport // user, mdp 
    {
        $email = $request->request->get('email', '');

        // insert le dernier utilisateur
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email), // chercher l'utilisateur par email
            new PasswordCredentials($request->request->get('password', '')), // recup le password taper
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),   // jeton de secu comme quoi el formulaire vient bien de mon site
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_main'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    // rediriger si pas bon
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}