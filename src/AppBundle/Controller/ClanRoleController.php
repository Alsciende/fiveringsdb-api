<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ClanRole;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ClanRoleController
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class ClanRoleController extends AbstractController
{
    /**
     * Get all pairs Clan-Role
     * @Route("/clan-roles")
     * @Method("GET")
     */
    public function listAction (Request $request, EntityManagerInterface $entityManager)
    {
        $this->setPublic($request);

        return $this->success(
            $entityManager
                ->getRepository(ClanRole::class)
                ->findAll(),
            [
                'Default',
                'Card',
                'card' => [
                    'Id',
                ],
            ]
        );
    }

}
