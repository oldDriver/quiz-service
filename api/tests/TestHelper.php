<?php
namespace App\Tests;

use App\Dto\DAT\Dossier;
use App\Dto\DAT\Vehicle;
use App\Dto\DAT\ManufacturerName;
use App\Dto\DAT\BaseModelName;
use App\Dto\DAT\SubModelName;
use App\Dto\DAT\DATECodeEquipment;
use App\Dto\DAT\EquipmentPosition;
use App\Dto\DAT\KbaNumbersN;
use App\Dto\DAT\VINColor;
use App\Dto\DAT\VINColors;
use App\Dto\DAT\VINResult;
use App\Dto\DAT\SeriesEquipment;
use App\Dto\DAT\Equipment;
use App\Dto\DAT\SpecialEquipment;
use App\Entity\Reference\Upholstery;

class TestHelper
{
    public const ADMIN_STRING = 'a1144e94-13d3-4512-be36-e8b85300d1ab';

    public const USER_STRING = 'e0f9a16d-ae9f-4098-bf91-de771ebfcddd';

    public const MICHAEL_CORLEONE = "e79d1d7a-f948-4f52-984d-c0818159bder";

    /**
     * This method allow to test private methods
     * @param object $object
     * @param string $methodName
     * @param array $args
     * @return mixed
     */
    public static function callMethod($object, string $methodName, array $args = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
    
    /**
     * Set private propery for the class after we disable original construtor
     * @param object $object
     * @param string $propertyName
     * @param mixed $value
     * @return object
     */
    public static function setProperty($object, string $propertyName, $value)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
        return $object;
    }
    
    /**
     * Get non public property
     * @param object $object
     * @param string $propertyName
     * @return mixed
     */
    public static function getProperty($object, string $propertyName)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * @param int $min
     * @param int $max
     * @return string
     */
    public static function getTestString(int $min = 10, int $max = 12): string
    {
        return substr(bin2hex(random_bytes(rand(5, 10))), 0, random_int($min, $max));
    }

    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function getTestInt(int $min = 1, int $max = 100): int
    {
        return random_int($min, $max);
    }

    /**
     * @return bool
     */
    public static function getTestBool(): bool
    {
        return (bool)random_int(0, 1);
    }

    public static function getDatVehicle(): Dossier
    {
        $dossier = new Dossier();
        $dossier->setCountry('DE');
        $dossier->setLanguage('de');
        $vehicle = new Vehicle();
        // Car Make
        $manufactureName = new ManufacturerName();
        $manufactureName->setOrigin('dat');
        $manufactureName->setValue('Test Car Make');// Audi
        $vehicle->setManufacturerName($manufactureName);
        // Car Model
        $vehicle->setMainTypeGroupName('Test Base Car Model');// A1
        // modelVersion
        $baseModelName = new BaseModelName();
        $baseModelName->setOrigin('dat');
        $baseModelName->setValue('Test Car Model Name');//A4 Avant (8K5)(04.2008->)
        $vehicle->setBaseModelName($baseModelName);
        // DATECodeEquipment
        $DATECodeEquipment = new DATECodeEquipment();
        // Doors
        $doors = new EquipmentPosition();
        $doors->setEquipmentClass(2);
        $doors->setDescription('Karosserie: 4-türig');
        // Transmission
        $transmission = new EquipmentPosition();
        $transmission->setEquipmentClass(11);
        $transmission->setDescription('Getriebe 6-Gang');
        // PowerKw
        $powerKw = new EquipmentPosition();
        $powerKw->setEquipmentClass(1);
        $powerKw->setDescription('Motor 2,0 Ltr. - 110 kW TDI');
        $DATECodeEquipment->addEquipmentPosition($transmission);
        $DATECodeEquipment->addEquipmentPosition($doors);
        $DATECodeEquipment->addEquipmentPosition($powerKw);
        $vehicle->setDATECodeEquipment($DATECodeEquipment);
        // TYsn/Hsn
        $KbaNumbersN = new KbaNumbersN();
        $KbaNumbersN->setKbaNumber(['0603/BQS']);
        $vehicle->setKbaNumbersN($KbaNumbersN);

        $vinColors = new VINColors();

        $bodyColor = new VINColor();
        $bodyColor->setCode('777');
        $bodyColor->setColorID('A1');
        $bodyColor->setDescription('Body color');

        $interiorColor = new VINColor();
        $interiorColor->setColorID('I1');
        $interiorColor->setCode('000');
        $interiorColor->setDescription('Interior Color');

        $upholstery = new VINColor();
        $upholstery->setColorID('PM');
        $upholstery->setCode('');
        $upholstery->setDescription('Upholstery');

        $vinColors->addVINColor($bodyColor);
        $vinColors->addVINColor($interiorColor);
        $vinColors->addVINColor($upholstery);

        $vinResult = new VINResult();
        $vinResult->setVINColors($vinColors);
        $vehicle->setVINResult($vinResult);

        $seriesEquipment = new SeriesEquipment();
        $specialEquipment = new SpecialEquipment();

        $equipment = new Equipment();
        $equipment->setSeriesEquipment($seriesEquipment);
        $equipment->setSpecialEquipment($specialEquipment);
        $vehicle->setEquipment($equipment);

        $dossier->setVehicle($vehicle);
        return $dossier;
    }
}
