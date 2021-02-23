<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\CheckIn;
use App\Entity\Device;
use App\Entity\DeviceType;
use App\Entity\Room;
use App\Form\AssetDeviceType;
use App\Form\BuildingType;
use App\Form\DeviceFormType;
use App\Form\RoomType;
use App\Repository\BuildingRepository;
use App\Repository\DeviceTypeRepository;
use App\Repository\RoomRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class AssetController extends AbstractController
{
    /**
     * @Route("/asset", name="asset")
     */
    public function index(): Response
    {
        return $this->render('asset/index.html.twig', [
            'controller_name' => 'AssetController',
        ]);
    }

    /**
     * @param BuildingRepository $repository
     * @return Response
     * @Route("/building/index",name="asset_building_index")
     */
    public function indexBuilding(BuildingRepository $repository)
    {
        $buildings = $repository->findAll();
        return $this->render(
            "asset/index_building.html.twig", [
                "buildings" => $buildings
            ]
        );
    }

    /**
     * @param Request $request
     * @Route("/building/new",name="asset_building_new")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newBuilding(Request $request, EntityManagerInterface $entityManager)
    {
        $building = new Building();
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($building);
            $entityManager->flush();
            return $this->redirectToRoute("asset_building_index");
        }
        return $this->render(
            "asset/new_building.html.twig", [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/building/edit/{id}",name="asset_building_edit")
     */
    public function editBuilding(Request $request,$id, EntityManagerInterface $entityManager){
        $building = $entityManager->getRepository(Building::class)->find($id);
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("asset_building_index");
        }
        return $this->render(
            "asset/new_building.html.twig", [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/building/delete/{id}",name="asset_building_delete")
     */
    public function deleteBuilding(EntityManagerInterface $entityManager,$id){
        $building = $entityManager->getRepository(Building::class)->find($id);
        $entityManager->remove($building);
        $entityManager->flush();
        return $this->redirectToRoute("asset_building_index");
    }

    /**
     * @param RoomRepository $repository
     * @return Response
     * @Route("/room/index",name="asset_room_index")
     */
    public function indexRoom(RoomRepository $repository)
    {
        $rooms = $repository->findAll();
        return $this->render(
            "asset/index_room.html.twig", [
                "rooms" => $rooms,
                "check_in" => false
            ]
        );
    }

    /**
     * @param Request $request
     * @Route("/room/new",name="asset_room_new")
     */
    public function newRoom(Request $request, EntityManagerInterface $entityManager)
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($room);
            $entityManager->flush();
            return $this->redirectToRoute("asset_room_index");
        }
        return $this->render(
            "asset/new_room.html.twig", [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/room/edit/{id}",name="asset_room_edit")
     */
    public function editRoom(Request $request,$id, EntityManagerInterface $entityManager)
    {
        $room = $entityManager->getRepository(Room::class)->find($id);
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("asset_room_index");
        }
        return $this->render(
            "asset/new_room.html.twig", [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @param $
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @Route("/room/delete/{id}",name="asset_room_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteRoom($id, EntityManagerInterface $entityManager){
        try{
            $room = $entityManager->getRepository(Room::class)->find($id);
            $entityManager->remove($room);
            $entityManager->flush();
            $this->addFlash("success","Deleted sucessfully ".$room->getName());
        }catch (\Exception $exception){
            $this->addFlash("error","Error occoured deleting".$room->getName());
        }
        return $this->redirectToRoute("asset_room_index");
    }

    /**
     * @Route("/room/view/{id}",name="asset_room_view")
     */
    public function viewRoom(Request $request, $id, EntityManagerInterface $entityManager)
    {
        $room = $entityManager->getRepository(Room::class)->find($id);
        if (!$room) {
            throw  $this->createNotFoundException();
        }
        $device = new Device();
        $deviceForm = $this->createForm(AssetDeviceType::class, $device);
        $deviceForm->handleRequest($request);
        $device->setRoom($room)
            ->setStatus(1);
        if ($deviceForm->isSubmitted() && $deviceForm->isValid()) {
            $entityManager->persist($device);
            $entityManager->flush();
        }
        return $this->render(
            "asset/view_room.html.twig", [
                "device_form" => $deviceForm->createView(),
                "room" => $room
            ]
        );
    }

    /**
     * @Route("/room/device/delete/{id}/{dest}",name="asset_room_device_delete")
     */
    public function deleteRoomDevice($id, $dest)
    {
        $device = $this->getDoctrine()->getRepository(Device::class)
            ->find($id);
        $this->getDoctrine()->getManager()->remove($device);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("asset_room_view", ["id" => $dest]);
    }

    /**
     * @param DeviceTypeRepository $repository
     * @Route("/device/type/index",name="asset_device_type_index")
     * @return Response
     */
    public function indexDeviceType(DeviceTypeRepository $repository)
    {
        $types = $repository->findAll();
        return $this->render("asset/index_device_type.twig", [
            "types" => $types
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/device/type/new",name="asset_device_type_new")
     */
    public function newDeviceType(Request $request, EntityManagerInterface $entityManager)
    {
        $type = new DeviceType();
        $form = $this->createForm(DeviceFormType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();
            return $this->redirectToRoute("asset_device_type_index");
        }
        return $this->render(
            "asset/new_device_type.html.twig", [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/device/type/edit/{id}",name="asset_device_type_edit")
     */
    public function editDeviceType(Request $request,$id,EntityManagerInterface $entityManager){
        $type = $entityManager->getRepository(DeviceType::class)->find($id);
        $form = $this->createForm(DeviceFormType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("asset_device_type_index");
        }
        return $this->render(
            "asset/new_device_type.html.twig", [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/device/type/delete/{id}",name="asset_device_type_delete")
     */
    public function deleteDeviceType($id,EntityManagerInterface $entityManager){
        $type = $entityManager->getRepository(DeviceType::class)->find($id);
        if(!$type)throw $this->createNotFoundException();
        try{
            $entityManager->remove($type);
            $entityManager->flush();
            $this->addFlash("success","device type ".$type->getName()." was deleted sucessfully");
        }catch (\Exception $exception){
            $this->addFlash("error","Error deleting device type ".$type->getName());
        }

         return $this->redirectToRoute("asset_device_type_index");
    }

    /**
     * @param RoomRepository $repository
     * @return Response
     * @Route("/checkin",name="asset_checkin")
     */
    public function indexCheckIn(RoomRepository $repository)
    {
        $rooms = $repository->findAll();
        return $this->render(
            "asset/index_check_in.html.twig", [
                "rooms" => $rooms,
                "check_in" => true,
            ]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @Route("/checkin/room/{id}",name="asset_checkin_room")
     * @return Response
     */
    public function addCheckIn(Request $request, $id, EntityManagerInterface $entityManager)
    {
        $room = $entityManager->getRepository(Room::class)->find($id);
        if ($request->isMethod('post')) {
            $now = new \DateTime("now");
            $other = $entityManager->getRepository(CheckIn::class)->findOneBy([
                "checkedAt" => $now,
                "room" => $room
            ]);
            if ($other && (!$request->get("update"))) {
                $this->addFlash("error", "check in for this day was done");
            }
            else {
                if($devices= $request->request->get("device")){
                    $devices=array_keys($devices);
                }else{
                    $devices=[];
                }
                $access=true;
                if ($request->get("update")) {
                    $message ="Checkin Updated Successfully";
                    $checkinId = $request->get("checkinId");
                    $checkin = $entityManager->getRepository(CheckIn::class)->find($checkinId);
                    if (!$checkin) throw $this->createNotFoundException();
                    if($checkin->getCheckedAt()->format("y-m-d")===$now->format("y-m-d")){
                        foreach ($checkin->getDevices() as $device) {
                            $checkin->removeDevice($device);
                        }
                    }else{
                        $access=false;
                    }
                }
                else {
                    $message ="checkin created sucessfully";
                    $checkin = new CheckIn();
                    $checkin->setCheckedAt(new \DateTime("now"));
                    $checkin->setRoom($room);
                }
                if($access){
                    if ($devices) {
                        foreach ($devices as $device) {
                            $dev = $entityManager->getRepository(Device::class)->find($device);
                            if ($dev) {
                                $checkin->addDevice($dev);
                            }
                        }
                    }
                    $entityManager->persist($checkin);
                    $entityManager->flush();
                    $this->addFlash("success", $message);
                }else{
                    $this->addFlash("error","you can update only today schedule");
                }
            }
        }
        $checkins = $entityManager->getRepository(CheckIn::class)->findBy(
                ["room"=>$room],
                ["checkedAt"=>"DESC"]
            );
        return $this->render(
            "asset/update_checkin.html.twig", [
                "room" => $room,
                "check_ins"=>$checkins,
                "now"=>new \DateTime("now")
            ]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @Route("/checkin/room/checkall/{id}",name="asset_checkin_room_checkall")
     * @return Response
     */
    public function checkAll($id,EntityManagerInterface $entityManager,MarkdownParserInterface $markdownParser){
        $room = $entityManager->getRepository(Room::class)->find($id);
        if(!$room->getLastCheckin()){
            $checkin = new CheckIn();
            $checkin->setCheckedAt(new \DateTime("now"))
                ->setRoom($room);
            $devices = $room->getDevices();
            $res="<div>";
            foreach ($devices as $device){
                $name = $device->getType()->getName();
                $checkin->addDevice($device);
                $res.="<label class='chip-checked'>".$name."</label>&nbsp;";
            }
            $res.="</div>";
            $entityManager->persist($checkin);
            $entityManager->flush();
            $this->addFlash("success","checked in  these items ".$markdownParser->transformMarkdown($res));
        }else{
            $this->addFlash("error","cant add two checkins same day");
        }
        return $this->redirectToRoute("asset_checkin");
    }


    /**
     * @param Request $request
     * @todo with ajax
     * @Route("/checkin/room/{id}/update",name="asset_checkin_room_update")
     */
    public function updateCheckIn(Request $request)
    {

    }


}