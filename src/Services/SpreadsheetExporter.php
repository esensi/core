<?php

namespace Esensi\Core\Services;

use Carbon\Carbon;
use Esensi\Core\Contracts\ModelInjectedInterface;
use Esensi\Core\Contracts\PackagedInterface;
use Esensi\Core\Contracts\RepositoryInjectedInterface;
use Esensi\Core\Contracts\SpreadsheetExporterInterface;
use Esensi\Core\Traits\ModelInjectedTrait;
use Esensi\Core\Traits\PackagedTrait;
use Esensi\Core\Traits\RepositoryInjectedTrait;
use League\Csv\Writer;
use SplTempFileObject;

/**
 * Exports a spreadsheet to a CSV writer instance.
 *
 */
abstract class SpreadsheetExporter implements
    ModelInjectedInterface,
    PackagedInterface,
    RepositoryInjectedInterface,
    SpreadsheetExporterInterface {

    /**
     * Make this repository use injected models.
     *
     * @see Esensi\Core\Traits\ModelInjectedTrait
     */
    use ModelInjectedTrait;

    /**
     * Package this repository.
     *
     * @see Esensi\Core\Traits\PackagedTrait
     */
    use PackagedTrait;

    /**
     * Make this repository use injected repositories
     *
     * @see Esensi\Core\Traits\RepositoryInjectedTrait
     */
    use RepositoryInjectedTrait;

    /**
     * Writer that handles the CSV creation.
     *
     * @var League\Csv\Writer
     */
    protected $data;

    /**
     * Name of the spreadsheet.
     *
     * @var string
     */
    protected $name;

    /**
     * Header row for spreadsheet.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Create a new instance of the export writer.
     *
     * @return void
     */
    public function __construct()
    {
        // Create an in-memory CSV writer instance
        $this->data = Writer::createFromFileObject(new SplTempFileObject());
    }

    /**
     * Return the header row for the spreadsheet.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the name of the spreadsheet.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generate a spreadsheet by transforming 3D data into 2D data.
     *
     * @param array  $filters (optional)
     * @return League\Csv\Writer
     */
    public function generate(array $filters = [])
    {
        // Query the repository for filtered set of objects to export
        $objects = $this->query($filters);

        // Insert headers
        $this->data->insertOne( $this->getHeaders() );

        // Add objects as rows
        if ($objects) {
            // Transform a 3D object into a 2D row
            $objects->transform([$this, 'transform']);

            // Insert all the objects as rows
            $this->data->insertAll($objects->toArray());
        }

        return $this->data;
    }

    /**
     * Query for objects to export.
     *
     * @param array  $filters (optional)
     * @return Illuminate\Database\Eloquent\Collection|null
     */
    public function query(array $filters = [])
    {
        return $this->getModel()
            ->newQuery()
            ->get();
    }

    /**
     * Transform a 3D object into a 2D array.
     *
     * @param object  $object model
     * @return array
     */
    public function transform( $object )
    {
        // Flatten the object model
        $attributes = $object->toArray();

        // Add each of the values that corresponds with the headers
        $row = [];
        foreach ($this->getHeaders() as $attribute => $header) {
            // Use dot array access to get the column value
            $value = array_get($attributes, $attribute);

            // Finally add the value to the row
            array_push($row, $value);
        }

        // Return the 2D row
        return $row;
    }

    /**
     * Generate download response.
     *
     * @return Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download()
    {
        // Render CSV download
        $filename = Carbon::now()->format('Y-m-d H:i') . ' ' . ucwords($this->getName()) . '.csv';
        return $this->data->output($filename);
    }

}
