<?php

namespace Esensi\Core\Contracts;

/**
 * Spreadsheet Exporter Service Interface
 *
 */
interface SpreadsheetExporterInterface
{
    /**
     * Return the header row for the spreadsheet.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Return the name of the spreadsheet.
     *
     * @return string
     */
    public function getName();

    /**
     * Generate a spreadsheet by transforming 3D data into 2D data.
     *
     * @param  array  $filters (optional)
     * @return League\Csv\Writer
     */
    public function generate( array $filters = [] );

    /**
     * Query for objects to export.
     *
     * @param  array  $filters (optional)
     * @return Illuminate\Database\Eloquent\Collection|null
     */
    public function query( array $filters = [] );

    /**
     * Transform a 3D object into a 2D array.
     *
     * @param  object  $object model
     * @return array
     */
    public function transform( $object );

    /**
     * Generate download response.
     *
     * @return Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download();

}
