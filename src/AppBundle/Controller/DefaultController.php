<?php

namespace AppBundle\Controller;

use Fizzik\HotstatusPipeline;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Asset;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        return $this->redirectToRoute("heroes");
    }

    /**
     * @Route("/news", name="news")
     */
    public function newsAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/heroes", name="heroes")
     */
    public function heroesAction() {
        HotstatusPipeline::filter_generate_date();

        return $this->render('default/heroes.html.twig', [
            "filter_gameTypes" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_GAMETYPE],
            "filter_maps" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_MAP],
            "filter_ranks" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_RANK],
            "filter_dates" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_DATE]
        ]);
    }

    /**
     * @Route("/heroes/{heroProperName}", name="hero")
     */
    public function heroAction($heroProperName) {
        if (key_exists($heroProperName, HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_HERO])) {
            HotstatusPipeline::filter_generate_date();

            return $this->render('default/hero.html.twig', [
                "hero_name" => $heroProperName,
                "filter_gameTypes" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_GAMETYPE],
                "filter_maps" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_MAP],
                "filter_ranks" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_RANK],
                "filter_dates" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_DATE]
            ]);
        }
        else {
            return $this->redirectToRoute("heroes");
        }
    }

    /**
     * @Route("/talents", name="talents")
     */
    public function talentsAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/rankings", name="rankings")
     */
    public function rankingsAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }
}
