<?php

namespace App\Controller;

use App\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CourseType;

use function PHPUnit\Framework\throwException;

class CourseController extends AbstractController
{
    #[Route('/course', name: 'course_index')]
    public function index(): Response
    {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();

        return $this->render(
            'course/index.html.twig',
            [
                'courses' => $courses
            ]
        );
    }

    #[Route('/course/detail/{id}', name: 'course_detail')]
    public function ViewDetail($id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);

        return $this->render(
            'course/detail.html.twig',
            [
                'course' => $course
            ]
        );
    }
    #[Route('/course/delete/{id}', name: 'course_delete')]
    public function courseDelete ($id) {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($course == null) {
            $this->addFlash('Error', 'Student is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($course);
            $manager->flush();
            $this->addFlash('Success', 'Student has been deleted successfully !');   
        }
        return $this->redirectToRoute('student_index');
    }
    #[Route('/course/add', name: 'course_add')]
    public function AddCourse(Request $r)
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('Success', "Add new course successfully");
            return $this->redirectToRoute("course_index");
        }
        return $this->render(
            '/course/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
    #[Route('/course/edit/{id}', name: 'course_edit')]
    public function EditCourse(Request $r,int $id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('Success', "Add new course successfully");
            return $this->redirectToRoute("course_index");
        }
        return $this->render(
            '/course/edit.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
}
