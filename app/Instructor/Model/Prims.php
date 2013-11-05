<?php

abstract class App_Instructor_Model_Prims
{
    private static $sdk = null;
    
    private static $primThickness = 0.2;
    private static $primWidth = 9.5;
    private static $primHeight = 4.5;
    
    private static function getSdk()
    {
        if(self::$sdk === null) {
            self::$sdk = new Lib_OpenSimSdk('http://cc.ics.uci.edu/inspectworld/rest/api');
        }
        
        return self::$sdk;
    }
    
    public static function getAll($fields = null)
    {
        $sdk = self::getSdk();
        
        $res = $sdk->get('prims', array(
            'fields' => ($fields) ? $fields : 'Name,Description'
        ));
        
        return json_decode($res);
    }
    
    public static function add($idRegion, $idCreator, $x, $y, $zRotation, $description, $url, $z = 23.3)
    {
        $sdk = self::getSdk();
    
        $primPattern = array(
            'CreationDate' => time(),
            'Name' => 'API Browser', // not edit this
            'Text' => '',
            'Description' => $description,
            'SitName' => '',
            'TouchName' => '',
            'ObjectFlags' => 0,
            'OwnerMask' => 2147483647,
            'NextOwnerMask' => 2147483647,
            'GroupMask' => 0,
            'EveryoneMask' => 0,
            'BaseMask' => 2147483647,
            'PositionX' => 0,
            'PositionY' => 0,
            'PositionZ' => 0,
            'GroupPositionX' => 5, // to edit
            'GroupPositionY' => 5, // to edit
            'GroupPositionZ' => 23.3,
            'VelocityX' => 0,
            'VelocityY' => 0,
            'VelocityZ' => 0,
            'AngularVelocityX' => 0,
            'AngularVelocityY' => 0,
            'AngularVelocityZ' => 0,
            'AccelerationX' => 0,
            'AccelerationY' => 0,
            'AccelerationZ' => 0,
            'RotationX' => 0,
            'RotationY' => 0,
            'SitTargetOffsetX' => 0,
            'SitTargetOffsetY' => 0,
            'SitTargetOffsetZ' => 0,
            'SitTargetOrientW' => 1,
            'SitTargetOrientX' => 0,
            'SitTargetOrientY' => 0,
            'SitTargetOrientZ' => 0,
            'RegionUUID' => $idRegion,
            'CreatorID' => $idCreator,
            'OwnerID' => $idCreator,
            'GroupID' => '00000000-0000-0000-0000-000000000000',
            'LastOwnerID' => $idCreator,
            //'SceneGroupID' => '82a54e09-65df-3714-a3ed-e6847dc90f2b', // it's the same than the UUID (automatic)
            'PayPrice' => -2,
            'PayButton1' => -2,
            'PayButton2' => -2,
            'PayButton3' => -2,
            'PayButton4' => -2,
            'LoopedSound' => '00000000-0000-0000-0000-000000000000',
            'LoopedSoundGain' => 0,
            'TextureAnimation' => '',
            'OmegaX' => 0,
            'OmegaY' => 0,
            'OmegaZ' => 0,
            'CameraEyeOffsetX' => 0,
            'CameraEyeOffsetY' => 0,
            'CameraEyeOffsetZ' => 0,
            'CameraAtOffsetX' => 0,
            'CameraAtOffsetY' => 0,
            'CameraAtOffsetZ' => 0,
            'ForceMouselook' => 0,
            'ScriptAccessPin' => 0,
            'AllowedDrop' => 0,
            'DieAtEdge' => 0,
            'SalePrice' => 0,
            'SaleType' => 0,
            'ColorR' => 0,
            'ColorG' => 0,
            'ColorB' => 0,
            'ColorA' => 255,
            'ParticleSystem' => '',
            'ClickAction' => 0,
            'Material' => 3,
            'CollisionSound' => '00000000-0000-0000-0000-000000000000',
            'CollisionSoundVolume' => 0,
            'LinkNumber' => 0,
            'PassTouches' => 0,
            'MediaURL' => "x-mv:0000000000/" . $idCreator, // the number 00..00 is a counter for each edit
            'Shape' => 0,
            // these are the dimensions
            'ScaleX' => self::$primThickness, // the thickness
            'ScaleY' => self::$primWidth, // the width
            'ScaleZ' => self::$primHeight, // the height
            'PCode' => 9,
            'PathBegin' => 0,
            'PathEnd' => 0,
            'PathScaleX' => 100,
            'PathScaleY' => 100,
            'PathShearX' => 0,
            'PathShearY' => 0,
            'PathSkew' => 0,
            'PathCurve' => 16,
            'PathRadiusOffset' => 0,
            'PathRevolutions' => 0,
            'PathTaperX' => 0,
            'PathTaperY' => 0,
            'PathTwist' => 0,
            'PathTwistBegin' => 0,
            'ProfileBegin' => 0,
            'ProfileEnd' => 0,
            'ProfileCurve' => 1,
            'ProfileHollow' => 0,
            'State' => 0,
            'Texture' => file_get_contents('pub/bin/Texture'),
            'ExtraParams' => file_get_contents('pub/bin/ExtraParams'),
            'Media' => str_replace(
                    array('#CURRENT_URL#', '#HOME_URL#'), 
                    array($url, $url),
                    file_get_contents('pub/bin/Media'))
        );
        
        $primPattern['GroupPositionX'] = $x;
        $primPattern['GroupPositionY'] = $y;
        $primPattern['GroupPositionZ'] = $z;
        $primPattern['RotationZ'] = sin(deg2rad($zRotation/2));
        $primPattern['RotationW'] = cos(deg2rad($zRotation/2));
        
        $res = $sdk->post('prims', $primPattern);
        
        return json_decode($res);
    }
    
    /**
     *
     * @param type $idRegion
     * @param type $idCreator
     * @param array $steps array where each element is like array("description" => "...","url" => "...")
     * @param type $xStart
     * @param type $yStart
     * @param type $distanceBetweenPrims 
     */
    public static function buildArena($idRegion, $idCreator, array $steps, $xStart, $yStart, $distanceBetweenPrims, $scoreboardUrl)
    {
        $xStartOrig = $xStart;
        $yStartOrig = $yStart;
        
        $primsNum = count($steps);
        
        $north = ceil($primsNum/3); // prims in the north side
        $west = ceil(($primsNum-$north)/2); // prims in the west side
        $east = $primsNum-$north-$west; // prims in the east side

        // draw the west side
        $i = 0;
        for($i=0 ; $i<$west ; $i++)
        {
            $degrees = 0;
            $description = $steps[$i]['description'];
            $url = $steps[$i]['url'];
            
            App_Instructor_Model_Prims::add($idRegion, $idCreator, $xStart, $yStart, $degrees, $description, $url);
            
            $yStart += self::$primWidth + $distanceBetweenPrims;
        }
        
        // draw the north side
        $xStart += self::$primWidth + $distanceBetweenPrims;
        for($j=$i ; $j<$north+$i ; $j++)
        {
            $degrees = 270;
            $description = $steps[$j]['description'];
            $url = $steps[$j]['url'];
            
            App_Instructor_Model_Prims::add($idRegion, $idCreator, $xStart, $yStart, $degrees, $description, $url);
            
            $xStart += self::$primWidth + $distanceBetweenPrims;
        }
        
        // draw the east side
        $yStart -= (self::$primWidth + $distanceBetweenPrims);
        for($z=$j ; $z<$east+$j ; $z++)
        {
            $degrees = 180;
            $description = $steps[$z]['description'];
            $url = $steps[$z]['url'];
            
            App_Instructor_Model_Prims::add($idRegion, $idCreator, $xStart, $yStart, $degrees, $description, $url);
            
            $yStart -= (self::$primWidth + $distanceBetweenPrims);
        }
        
        // draw the score board
        $x = $xStart-($xStart-$xStartOrig)/2;
        App_Instructor_Model_Prims::add($idRegion, $idCreator, (int)$x, $yStartOrig, 90, 'Scoreboard', $scoreboardUrl);
    }
    
    public static function delete($uuid)
    {
        $sdk = self::getSdk();
        
        $res = $sdk->delete("prims/$uuid");

        return json_decode($res);
    }
}