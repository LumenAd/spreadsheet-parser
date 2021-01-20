<?php

namespace LumenAd\Component\SpreadsheetParser\Xlsx;

/**
 * Reads the worksheet list from a spreadsheet
 *
 * @author    Antoine Guigan <antoine@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class WorksheetListReader
{
    
     /* @var array */
    private static $namespaces = [
        'http://schemas.openxmlformats.org/officeDocument/2006/relationships',
        'http://purl.oclc.org/ooxml/officeDocument/relationships',
    ];
    
    
    /**
     * Returns the list of worksheets inside the archive
     *
     * The keys of the array should be the titles of the worksheets
     * The values of the array are the names of the XML worksheet files inside the archive
     *
     * @param Relationships $relationships
     * @param string        $path
     * 
     * @return array
     */
    public function getWorksheetPaths(Relationships $relationships, $path)
    {
        $xml = new \XMLReader();
        $xml->open($path);
        $paths = [];
        while ($xml->read()) {
            if (\XMLReader::ELEMENT === $xml->nodeType && 'sheet' === $xml->name) {
                $rId = null;
                foreach(self::$namespaces as $namespace) {
                    $rId = $xml->getAttributeNs('id', $namespace);
                    if($rId) {
                        break;
                    }
                }
                if(empty($rId)) {
                    break;
                }
                $paths[$xml->getAttribute('name')] = $relationships->getWorksheetPath($rId);
            }
        }

        return $paths;
    }
}
