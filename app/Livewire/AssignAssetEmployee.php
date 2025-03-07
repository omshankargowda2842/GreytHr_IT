<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\ActivityLog;
use App\Models\asset_types_table;
use App\Models\AssetAssignments;
use App\Models\AssetsHistories;
use App\Models\AssignAssetEmp;
use App\Models\EmployeeDetails;
use App\Models\VendorAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AssignAssetEmployee extends Component
{
    use WithFileUploads;
    public $assetSelect = [];
    public $assetSelectEmp = [];
    public $assignedAssetIds = [];
    public $assignedEmployeeIds = [];
    public $selectedAsset = null;
    public $assetDetails = null;
    public $empDetails = null;
    public $selectedEmployee = null;
    public $isUpdateMode = false;
    public $employeeAssetListing = true;
    public $searchFilters = true;
    public $assetEmpCreateUpdate = false;
    public $oldEmpAssetListing = false;
    public $assignmentId;
    public $searchEmp = '';
    public $searchAssetId = '';
    public $filteredEmployeeAssets = [];
    public $assetsFound = false;
    public $showEditDeleteEmployeeAsset = true;
    public $showEMployeeAssetBtn = false;
    public $showAssignAssetBtn = true;
    public $showOldEMployeeAssetBtn = true;
    public $showLogoutModal = false;
    public $oldAssetEmp = false;
    public $showSystemUpdateForm = false;
    public $systemName;
    public $macAddress;
    public $laptopReceived;
    public $sophosAntivirus = "";
    public $vpnCreation = "";
    public $teramind = "";
    public $systemUpgradation = "";
    public $oneDrive = "";
    public $screenshotPrograms = "";
    public $isRotated = false;
    public $showOverview = false;
    public $categories;
    public $selectedCategory = "";
    public $showViewEmployeeAsset = false;
    public $currentVendorId = null;
    public $selectedStatus = '';
    public $deleteAsset_id;
    public $previousAssignedAssets;
    public $showSuccessMsg=false;
    public $previews=[];
    public $all_files = [];
    public $it_file_paths = [];
    public $file_paths = []; // Array to store the uploaded files


    public function oldAssetlisting()
    {

        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = false;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = false;
        $this->showOldEMployeeAssetBtn = false;
        $this->searchEmp = "";
        $this->oldAssetEmp = true;
    }

    public function assetlisting()
    {
        $this->showOldEMployeeAssetBtn = true;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = true;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = true;
        $this->showOldEMployeeAssetBtn = false;
    }
    public function assignAsset()
    {
        $this->resetForm();
        $this->assetEmpCreateUpdate = true;
        $this->oldAssetEmp = false;
        $this->employeeAssetListing = false;
        $this->showAssignAssetBtn = false;
        $this->showEMployeeAssetBtn = true;
        $this->showOldEMployeeAssetBtn = false;
        $this->showEditDeleteEmployeeAsset = false;
        $this->showSystemUpdateForm = false;
        $this->isUpdateMode = false;
        $this->resetErrorBag(['selectedAsset', 'selectedEmployee']);
    }

    protected function rules()
    {
        return [
            'selectedAsset' => 'required|string|max:255',
            'selectedEmployee' => 'required|string|max:255',
        ];
    }
    public $rules = [

        'reason' => 'required|string|max:255', // Validate the remark input
        'selectedStatus' => 'required',

    ];


    protected $messages = [
        'selectedAsset.required' => 'Asset Id is required.',
        'selectedEmployee.required' => 'Employee Id is required.',
        'reason.required' => 'Reason is required.',
        'selectedStatus.required' => 'Asset status is required.',
    ];


    public function viewDetails($employeeAssetList)
    {

        $this->searchFilters = false;
        $this->showOldEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = false;
        $this->currentVendorId = $employeeAssetList;
        $this->showViewEmployeeAsset = true;
        $this->showEditDeleteEmployeeAsset = false;
        // $this->editMode = false;
    }

    public $oldAssetBackButton = true;
    public function viewOldAssetDetails($employeeAssetList)
    {
        $this->searchFilters = false;
        $this->oldAssetBackButton = false;
        $this->showOldEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = false;
        $this->currentVendorId = $employeeAssetList;
        $this->showViewEmployeeAsset = true;
        $this->showEditDeleteEmployeeAsset = false;
    }

    public function closeViewVendor()
    {
        $this->resetForm();
        $this->searchFilters = true;
        $this->showOldEMployeeAssetBtn = true;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = true;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = true;
        $this->showLogoutModal = false;
        $this->showViewEmployeeAsset = false;
        $this->showEditDeleteEmployeeAsset = true;
        $this->currentVendorId = null;
        $this->oldAssetEmp = false;
        $this->isUpdateMode = false;
        $this->searchEmp = "";
        return redirect()->route('employeeAssetList');
    }

    public function backVendor()
    {
        $this->resetForm();
        $this->searchFilters = true;
        $this->showOldEMployeeAssetBtn = true;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = true;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = true;
        $this->showLogoutModal = false;
        $this->showViewEmployeeAsset = false;
        $this->showEditDeleteEmployeeAsset = true;
        $this->currentVendorId = null;
        $this->oldAssetEmp = false;
        $this->isUpdateMode = false;
        $this->validateOnly('selectedAsset');
        $this->validateOnly('selectedEmployee');

        $this->resetErrorBag(['selectedAsset', 'selectedEmployee']);
    }

    public function closeViewEmpAsset()
    {
        $this->resetForm();
        $this->searchFilters = true;
        $this->oldAssetBackButton = true;
        $this->showOldEMployeeAssetBtn = false;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = false;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = false;
        $this->showLogoutModal = false;
        $this->showViewEmployeeAsset = false;
        $this->showEditDeleteEmployeeAsset = true;
        $this->currentVendorId = null;
        $this->oldAssetEmp = true;
        $this->isUpdateMode = false;
    }



    private function resetForm()
    {
        $this->sophosAntivirus = null;
        $this->vpnCreation = null;
        $this->teramind = null;
        $this->systemUpgradation = null;
        $this->oneDrive = null;
        $this->screenshotPrograms = null;
        $this->macAddress = '';
        $this->laptopReceived = null;
        $this->selectedAsset = null;
        $this->selectedEmployee = null;
        $this->assetDetails = null;
        $this->empDetails = null;
        $this->isUpdateMode = false;
        $this->assignmentId = null;
    }

    public function mount()
    {
        try {
            $this->categories = asset_types_table::select('id', 'asset_names')->get();
            $this->loadAssetsAndEmployees();
        } catch (\Exception $e) {
            Log::error("Error during mount method execution: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while loading categories or assets.');
            $this->categories = [];
        }
    }




    public function updatedFilePaths($value)
{
    $this->handleFileSelection($value);
}

public function handleFileSelection($value)
{
    foreach ($this->file_paths as $file) {
        // Ensure no duplicate files are added
        $existingFileNames = array_map(function ($existingFile) {
            return $existingFile->getClientOriginalName();
        }, $this->all_files);

        if (!in_array($file->getClientOriginalName(), $existingFileNames)) {
            // Append only new files to all_files
            $this->all_files[] = $file;

            try {
                // Generate previews only for the new file
                if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])) {
                    $base64Image = base64_encode(file_get_contents($file->getRealPath()));
                    $this->previews[] = [
                        'url' => 'data:' . $file->getMimeType() . ';base64,' . $base64Image,
                        'type' => 'image',
                        'name' => $file->getClientOriginalName(),
                    ];

                } else {
                    $this->previews[] = [
                        'type' => 'file',
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            } catch (\Throwable $th) {
                Log::error('Error generating preview:', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $th->getMessage(),
                ]);
            }
        }
    }

    $this->showSuccessMsg = true;
    $this->showFilePreviewModal = true;
}


    public function updatedItFilePaths($value , $fileid)
{
    // dd($fileid);

    // Assuming it_file_paths is an array of files for a specific request ID
    foreach ($this->it_file_paths[$fileid] as $file) {
        // Ensure no duplicate files are added
        $existingFileNames = array_map(function ($existingFile) {
            return $existingFile->getClientOriginalName();
        }, $this->all_files);

        if (!in_array($file->getClientOriginalName(), $existingFileNames)) {
            // Append only new files to all_files
            $this->all_files[] = $file;

            try {
                // Generate previews only for the new file
                if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])) {
                    $base64Image = base64_encode(file_get_contents($file->getRealPath()));
                    $this->previews[] = [
                        'url' => 'data:' . $file->getMimeType() . ';base64,' . $base64Image,
                        'type' => 'image',
                        'name' => $file->getClientOriginalName(),
                    ];

                } else {
                    $this->previews[] = [
                        'type' => 'file',
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            } catch (\Throwable $th) {
                Log::error('Error generating preview:', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $th->getMessage(),
                ]);
            }
        }
    }


    $this->showSuccessMsg = true;
    $this->showFilePreviewModal = true;
    // $this->selectedRecordId = $recordId;

}

    public $showFilePreviewModal = false;



        public function hideFilePreviewModal()
    {
        // Hide the modal by setting the property to false
        $this->showFilePreviewModal = false;
    }

    public function  hideSuccessMsg(){
        $this->showSuccessMsg=false;
    }

    public function removeFile($index)
    {
        if (isset($this->all_files[$index])) {
            unset($this->all_files[$index]);
            unset($this->previews[$index]);
            $this->all_files = array_values($this->all_files);
            $this->previews = array_values($this->previews);
        }

    }


    public $selectedRecordId ;  // Add this to store the selected record's ID

// When opening the file preview modal


    public function uploadFiles($selectedRecordId)
    {

        // dd($selectedRecordId);

        $this->it_file_paths = $this->all_files;

        $attachments = AssignAssetEmp::find($selectedRecordId);

        // Initialize fileDataArray
        $fileDataArray = [];

        if ($this->it_file_paths) {
            foreach ($this->it_file_paths as $file) {
                $mimeType = $file->getMimeType();

                // Check if the file is an image
                if (strpos($mimeType, 'image/') === 0) {
                    // Validate image file size
                    if ($file->getSize() > 1024 * 1024) { // 1 MB in bytes
                        FlashMessageHelper::flashError("The image {$file->getClientOriginalName()} exceeds the 1 MB size limit.");
                        return; // Stop further processing
                    }
                } else {
                    // Validate non-image file size
                    if ($file->getSize() >  100 * 1024 * 1024) { // 100 MB in bytes
                        FlashMessageHelper::flashError("The file {$file->getClientOriginalName()} exceeds the 100 MB size limit.");
                        return; // Stop further processing
                    }
                }
            }

            // Validate files based on their types
            $this->validate([
                'it_file_paths.*' => [
                    'nullable',
                    'file',
                    'mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif',
                    'max:512000', // Maximum size in KB (500 MB for general validation)
                ],
            ]);
        }


        if ($this->it_file_paths) {




            // Process each file
            foreach ($this->it_file_paths as $file) {
                try {
                    if ($file->isValid()) {
                        $fileContent = file_get_contents($file->getRealPath());
                        $mimeType = $file->getMimeType();
                        $base64File = base64_encode($fileContent);

                        // Add new file to the array
                        $fileDataArray[] = [
                            'data' => $base64File,
                            'mime_type' => $mimeType,
                            'original_name' => $file->getClientOriginalName(),
                        ];


                    } else {
                        Log::error('File is not valid:', ['file' => $file->getClientOriginalName()]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing file:', [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }


        // If the record exists, update the file paths

        if ($attachments) {
            try {
                // Log before update
                Log::info('Attempting to update attachments', [
                    'attachment_id' => $attachments->id ?? null, // Log the ID if available
                    'existing_it_file_paths' => $attachments->asset_deactivate_file_path, // Log existing file paths
                    'new_it_file_paths' => $fileDataArray, // Log the new file data array
                ]);

                // Perform the update
                // dd(json_encode($fileDataArray));

                $existingFiles = json_decode($attachments->asset_deactivate_file_path, true) ?? [];
                $allFiles = array_merge($existingFiles, $fileDataArray);


                $attachments->asset_deactivate_file_path=json_encode($allFiles);
                $attachments->save();

                FlashMessageHelper::flashSuccess("Files uploaded successfully!");
                $this->showFilePreviewModal = false;


                $employee = auth()->guard('it')->user();
                $assigneName = $employee->employee_name;
                // ActivityLog::create([
                //     'request_id' => $attachments->snow_id, // Assuming this is the request ID
                //     'description' => "Uploaded file: {$file->getClientOriginalName()}",
                //     'performed_by' => $assigneName,
                //     'attachments' => json_encode($fileDataArray)
                // ]);


                // Log successful update
                Log::info('Attachments updated successfully', [
                    'attachment_id' => $attachments->id ?? null,
                    'updated_it_file_paths' => json_encode($fileDataArray),
                ]);
                // Optional: return a success message or redirect


                $this->previews=[];
                $this->all_files = [];

            } catch (\Exception $e) {
                // Log any errors
                Log::error('Error updating attachments', [
                    'error_message' => $e->getMessage(),
                    'attachment_id' => $attachments->id ?? null,
                    'new_it_file_paths' => $fileDataArray,
                ]);

                // Optionally, rethrow the exception if needed
                throw $e;
            }
        }


    }




public $showViewImageDialog = false;
public $showViewEmpImageDialog = false;
public $currentassetID;
public $showViewFileDialog = false;
public $showViewEmpFileDialog = false;
public function closeViewFile()
{
    $this->showViewFileDialog = false;
}
public function showViewImage($assetID)
{
    $this->currentassetID = $assetID;
    $this->showViewImageDialog = true;
}


public function showViewFile($assetID)
{
    $this->currentassetID = $assetID;
    $this->showViewFileDialog = true;
}

public function closeViewImage()
{
    $this->showViewImageDialog = false;
}



public function showViewEmpImage($assetID)
{
    $this->currentassetID = $assetID;
    $this->showViewEmpImageDialog = true;
}



public function showViewEmpFile($assetID)
{
    $this->currentassetID = $assetID;
    $this->showViewEmpFileDialog = true;
}


public function closeViewEmpImage()
{
    $this->showViewEmpImageDialog = false;
}

public function closeViewEmpFile()
{
    $this->showViewEmpFileDialog = false;
}


public function downloadImages($assetID)
{
    try {

        $selectedAssetID = collect($this->allActiveAssetDetails)->firstWhere('id', $assetID);

        if (!$selectedAssetID) {
            // selectedAssetID not found
            return response()->json(['message' => 'selectedAssetID not found'], 404);
        }

        $fileDataArray = is_string($selectedAssetID->asset_assign_file_path)
            ? json_decode($selectedAssetID->asset_assign_file_path, true)
            : $selectedAssetID->asset_assign_file_path;

        // Filter images
        $images = array_filter(
            $fileDataArray,
            function ($fileData) {
                // Ensure 'mime_type' key exists and is valid
                return isset($fileData['mime_type']) && strpos($fileData['mime_type'], 'image') !== false;
            }
        );

        // If only one image, provide direct download
        if (count($images) === 1) {
            $image = reset($images); // Get the single image
            $base64File = $image['data'];
            $mimeType = $image['mime_type'];
            $originalName = $image['original_name'];

            // Decode base64 content
            $fileContent = base64_decode($base64File);

            // Return the image directly
            return response()->stream(
                function () use ($fileContent) {
                    echo $fileContent;
                },
                200,
                [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'attachment; filename="' . $originalName . '"',
                ]
            );
        }

        // If multiple images, create a ZIP file
        if (count($images) > 1) {
            $zipFileName = 'images.zip';
            $zip = new \ZipArchive();
            $zip->open(storage_path($zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach ($images as $image) {
                $base64File = $image['data'];
                $mimeType = $image['mime_type'];
                $extension = explode('/', $mimeType)[1];
                $imageName = uniqid() . '.' . $extension;

                $zip->addFromString($imageName, base64_decode($base64File));
            }

            $zip->close();

            return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
        }

        // If no images, return an appropriate response
        return response()->json(['message' => 'No images found'], 404);
    } catch (\Exception $e) {
        // Handle any exception that occurs and return a proper response
        return response()->json(['message' => 'An error occurred while processing the images', 'error' => $e->getMessage()], 500);
    }
}



public function downloadITImages($assetID)
{


    try {

        $selectedAssetID = collect($this->allAssetDetails)->firstWhere('id', $assetID);

        if (!$selectedAssetID) {
            // selectedAssetID not found
            return response()->json(['message' => 'selectedAssetID not found'], 404);
        }

        $fileDataArray = is_string($selectedAssetID->asset_deactivate_file_path)
            ? json_decode($selectedAssetID->asset_deactivate_file_path, true)
            : $selectedAssetID->asset_deactivate_file_path;


        // Filter images
        $images = array_filter(
            $fileDataArray,
            function ($fileData) {
                // Ensure 'mime_type' key exists and is valid
                return isset($fileData['mime_type']) && strpos($fileData['mime_type'], 'image') !== false;
            }
        );


        // If only one image, provide direct download
        if (count($images) === 1) {
            $image = reset($images); // Get the single image
            $base64File = $image['data'];
            $mimeType = $image['mime_type'];
            $originalName = $image['original_name'];

            // Decode base64 content
            $fileContent = base64_decode($base64File);

            // Return the image directly
            return response()->stream(
                function () use ($fileContent) {
                    echo $fileContent;
                },
                200,
                [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'attachment; filename="' . $originalName . '"',
                ]
            );
        }

        // If multiple images, create a ZIP file
        if (count($images) > 1) {
            $zipFileName = 'images.zip';
            $zip = new \ZipArchive();
            $zip->open(storage_path($zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach ($images as $image) {
                $base64File = $image['data'];
                $mimeType = $image['mime_type'];
                $extension = explode('/', $mimeType)[1];
                $imageName = uniqid() . '.' . $extension;

                $zip->addFromString($imageName, base64_decode($base64File));
            }

            $zip->close();

            return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
        }

        // If no images, return an appropriate response
        return response()->json(['message' => 'No images found'], 404);
    } catch (\Exception $e) {
        // Handle any exception that occurs and return a proper response
        return response()->json(['message' => 'An error occurred while processing the images', 'error' => $e->getMessage()], 500);
    }
}






    public function updatedSelectedCategory($value)
{

    $this->selectedCategory = $value;
    $this->selectedAsset = null;
    $this->fetchAssetDetails();

}

    public function loadAssets()
    {
        try {
            if ($this->selectedCategory == null || $this->selectedCategory == '') {

                // Load all assets if no category is selected
                $this->assetSelect = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
                    ->where('vendor_assets.is_active', 1)
                    ->where(function ($query) {
                        $query->whereIn('vendor_assets.status', ['In Stock', 'Available'])
                        ->orWhereNull('vendor_assets.status');  // Include rows where status is NULL
                    })
                    ->select('vendor_assets.asset_id', 'asset_types_tables.asset_names')
                    ->get();
            } else {

                // Load assets based on selected category
                $this->assetSelect = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
                    ->where('vendor_assets.is_active', 1)
                    ->where('asset_types_tables.id', $this->selectedCategory)
                    ->where(function ($query) {
                        $query->where('vendor_assets.status', '!=', 'In Repair')
                            ->orWhereNull('vendor_assets.status'); // Include rows where status is NULL
                    })
                    ->select('vendor_assets.asset_id', 'asset_types_tables.asset_names')
                    ->get();
            }
            // dd(  $this->assetSelect);

        } catch (\Exception $e) {
            // Log the error message
            Log::error("Error loading assets: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while loading assets.');
            $this->assetSelect = collect(); // Set to an empty collection if there's an error
        }
    }



    public function loadAssetsAndEmployees()
    {
        try {
            // Fetching employee details
            $this->assetSelectEmp = EmployeeDetails::where('status', 1)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            // Fetching assigned asset IDs and employee IDs
            $this->assignedAssetIds = AssignAssetEmp::where('is_active', 1)->pluck('asset_id')->toArray();
            $this->assignedEmployeeIds = AssignAssetEmp::where('is_active', 1)->pluck('emp_id')->toArray();
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error loading assets and employees: " . $e->getMessage(), [
                'exception' => $e,
            ]);

            FlashMessageHelper::flashError('An error occurred while loading assets and employees.');
            $this->assetSelectEmp = collect();
            $this->assignedAssetIds = [];
            $this->assignedEmployeeIds = [];
        }
    }


    public function fetchEmployeeDetails()
    {
        try {
            if ($this->selectedEmployee !== "" && $this->selectedEmployee !== null) {
                // Fetch employee details by ID
                $this->resetErrorBag();
                $this->empDetails = EmployeeDetails::find($this->selectedEmployee);

                if ($this->selectedCategory && $this->selectedEmployee) {
                    $this->getPreviousAssignedAssets();
                }
            } else {
                $this->empDetails = null;
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error("Error fetching employee details: " . $e->getMessage(), [
                'exception' => $e,
            ]);

            FlashMessageHelper::flashError('An error occurred while fetching employee details.');
            $this->empDetails = null;
        }
    }


    public function fetchAssetDetails()
    {
        try {
            if ($this->selectedAsset !== "" && $this->selectedAsset !== null) {
                $this->resetErrorBag();
                // Fetch asset details by asset ID
                $this->assetDetails = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
                    ->where('vendor_assets.asset_id', $this->selectedAsset)
                    ->select('vendor_assets.*', 'asset_types_tables.asset_names as asset_type_name')
                    ->first();
                $this->selectedCategory = $this->assetDetails->asset_type;
                if ($this->selectedCategory && $this->selectedEmployee) {
                    $this->getPreviousAssignedAssets();
                }
            } else {
                $this->assetDetails = null;
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error fetching asset details: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while fetching asset details.');
            $this->assetDetails = null;
        }
    }

    public function getPreviousAssignedAssets()
    {
       $this->previousAssignedAssets=AssignAssetEmp::join('asset_types_tables', 'assign_asset_emps.asset_type', '=', 'asset_types_tables.id')
       ->where('assign_asset_emps.asset_type', $this->selectedCategory)
       ->where('assign_asset_emps.emp_id',$this->selectedEmployee)
       ->where('assign_asset_emps.is_active',1)
       ->select('assign_asset_emps.*', 'asset_types_tables.asset_names as asset_type_name')
       ->get();

       if(count($this->previousAssignedAssets)>0){
        FlashMessageHelper::flashWarning('Already '. count($this->previousAssignedAssets).' ' . 'asset/s assigned with same Category.');

       }
    }



    public function edit($id)
    {
        try {
            // Set flags for creating/updating mode
            $this->showEMployeeAssetBtn = true;
            $this->showOldEMployeeAssetBtn = false;
            $this->showAssignAssetBtn = false;
            $this->assetEmpCreateUpdate = true;
            $this->employeeAssetListing = false;
            $this->showSystemUpdateForm = false;
            $this->isUpdateMode = true;

            // Fetch the assignment record
            $assignment = AssignAssetEmp::findOrFail($id);

            // Populate the component properties with the assignment data
            $this->assignmentId = $assignment->id;
            $this->selectedAsset = $assignment->asset_id;
            $this->selectedEmployee = $assignment->emp_id;

            $this->sophosAntivirus = $assignment->sophos_antivirus;
            $this->vpnCreation = $assignment->vpn_creation;
            $this->teramind = $assignment->teramind;
            $this->systemUpgradation = $assignment->system_upgradation;
            $this->oneDrive = $assignment->one_drive;
            $this->screenshotPrograms = $assignment->screenshot_programs;
            $this->macAddress = $assignment->mac_address;
            $this->laptopReceived = $assignment->laptop_received;

            $this->validateOnly('selectedAsset');
            $this->validateOnly('selectedEmployee');

            $this->resetErrorBag(['selectedAsset', 'selectedEmployee']);

            // Fetch asset and employee details
            $this->fetchAssetDetails();
            $this->fetchEmployeeDetails();
        } catch (\Exception $e) {
            // Log the error with detailed information
            Log::error("Error editing asset assignment: " . $e->getMessage(), [
                'exception' => $e,
                'assignment_id' => $id,
            ]);

            FlashMessageHelper::flashError('An error occurred while fetching or updating the asset assignment.');

            $this->reset(['selectedAsset', 'selectedEmployee', 'sophosAntivirus', 'vpnCreation', 'teramind', 'systemUpgradation', 'oneDrive', 'screenshotPrograms', 'macAddress', 'laptopReceived']);
        }
    }


    // public function getAssetOwners($assetId)
    //     {
    //         $currentOwner = AssignAssetEmp::where('asset_id', $assetId)
    //             ->where('is_active', true)
    //             ->first();

    //         $previousOwners = AssetAssignments::where('asset_id', $assetId)
    //             ->where('is_active', false)
    //             ->get();

    //         return response()->json([
    //             'currentOwner' => $currentOwner,
    //             'previousOwners' => $previousOwners
    //         ]);
    //     }


    public function toggleSystemUpdateForm()
    {
        $this->showSystemUpdateForm = !$this->showSystemUpdateForm;
        $this->isRotated = !$this->isRotated;
    }

    public function toggleOverview()
    {
        $this->showOverview = !$this->showOverview;
        $this->toggleSystemUpdateForm();
    }

    public $selectedAssetType = '';
    public function submit()
    {

        $this->validate();

        $this->validate([
            'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif', // Adjust max size as needed
        ]);

        $filePaths = $this->file_paths ?? [];
        $fileDataArray = [];

        $existingFiles = [];
        if ($this->isUpdateMode && $this->assignmentId) {
            $existingAssignment = AssignAssetEmp::find($this->assignmentId);
            if ($existingAssignment && $existingAssignment->asset_assign_file_path) {
                $existingFiles = json_decode($existingAssignment->asset_assign_file_path, true) ?? [];
            }
        }

        if (!empty($filePaths) && is_array($filePaths)) {
            foreach ($filePaths as $file) {
                if ($file->isValid()) {
                    try {
                        $fileDataArray[] = [
                            'data' => base64_encode(file_get_contents($file->getRealPath())),
                            'mime_type' => $file->getMimeType(),
                            'original_name' => $file->getClientOriginalName(),
                        ];
                    } catch (\Exception $e) {
                        Log::error('Error processing file', [
                            'file_name' => $file->getClientOriginalName(),
                            'error' => $e->getMessage(),
                        ]);
                        FlashMessageHelper::flashError('An error occurred while processing the file.');
                        return;
                    }
                } else {
                    FlashMessageHelper::flashError('Invalid file uploaded.');
                    return;
                }
            }
        }

        // Get the asset type of the currently assigned asset for the selected employee
        $selectedAssetType = AssignAssetEmp::where('emp_id', $this->selectedEmployee)
            ->where('is_active', 1)  // Add the condition for 'is_active'
            ->pluck('asset_type')  // Get the 'asset_type' column
            ->toArray();  // Convert to an array


        $existingAssignment = VendorAsset::where('asset_id', $this->selectedAsset)->value('asset_type');


        $assetName = asset_types_table::where('id', $existingAssignment)->value('asset_names');

        $employeeDetails = EmployeeDetails::find($this->selectedEmployee);
        $empId = $employeeDetails->emp_id;

        $uniqueManagerDeptHeads = EmployeeDetails::select('manager_id', 'dept_head')
            ->distinct()
            ->get()
            ->pluck('manager_id')  // Get the unique manager_id
            ->merge(EmployeeDetails::distinct()->pluck('dept_head'))  // Get the unique dept_head
            ->unique()  // Merge both columns and ensure uniqueness
            ->toArray();  // Convert to an array


        // if (in_array($empId , $uniqueManagerDeptHeads)) {




        // }
        // elseif( !$selectedAssetType  )
        // {
        // }
        //  else {

        //     If the asset type is already assigned to the employee, prevent assigning it again
        //     if (in_array($existingAssignment, $selectedAssetType)) {
        //         Show error message if the asset type already exists
        //         FlashMessageHelper::flashError("Asset type '{$assetName}' is already assigned to this Employee!");
        //         return;
        //     }

        // }

        $mergedFiles = array_merge($existingFiles, $fileDataArray);

        try {
            $emp_id = auth()->guard('it')->user()->emp_id;
            if ($this->isUpdateMode && $this->assignmentId) {
                // Retrieve the current assignment
                $currentAssignment = AssignAssetEmp::findOrFail($this->assignmentId);

                // Check if asset_id has changed
                if ($currentAssignment->asset_id !== $this->selectedAsset) {
                    // Archive the current assignment by setting is_active to false
                    $currentAssignment->update([
                        'is_active' => false,
                    ]);

                    // Create new assignment
                    AssignAssetEmp::create([
                        'asset_id' => $this->selectedAsset,
                        'emp_id' => $this->empDetails->emp_id,
                        'manufacturer' => $this->assetDetails->manufacturer,
                        'asset_type' => $this->assetDetails->asset_type,
                        'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                        'department' => $this->empDetails->job_role,
                        'asset_assign_file_path' => !empty($mergedFiles) ? json_encode($mergedFiles) : null,
                        'sophos_antivirus' => $this->sophosAntivirus,
                        'vpn_creation' => $this->vpnCreation,
                        'teramind' => $this->teramind,
                        'system_upgradation' => $this->systemUpgradation,
                        'one_drive' => $this->oneDrive,
                        'screenshot_programs' => $this->screenshotPrograms,
                        'mac_address' => $this->macAddress,
                        'laptop_received' => $this->laptopReceived,
                        'is_active' => true,
                    ]);

                    FlashMessageHelper::flashSuccess("Assignee updated successfully!");
                } else {
                    // No change in asset_id, so just update the existing assignment without creating a new record
                    $currentAssignment->update([
                        'emp_id' => $this->empDetails->emp_id,
                        'manufacturer' => $this->assetDetails->manufacturer,
                        'asset_type' => $this->assetDetails->asset_type,
                        'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                        'department' => $this->empDetails->job_role,
                        'asset_assign_file_path' => !empty($mergedFiles) ? json_encode($mergedFiles) : null,
                        'sophos_antivirus' => $this->sophosAntivirus,
                        'vpn_creation' => $this->vpnCreation,
                        'teramind' => $this->teramind,
                        'system_upgradation' => $this->systemUpgradation,
                        'one_drive' => $this->oneDrive,
                        'screenshot_programs' => $this->screenshotPrograms,
                        'mac_address' => $this->macAddress,
                        'laptop_received' => $this->laptopReceived,
                        'is_active' => true,
                    ]);

                    FlashMessageHelper::flashSuccess("Assignment updated successfully!");
                }
            } else {
                // Create new assignment as there is no existing one


                if ($this->selectedAsset) {
                    $selected_asset = VendorAsset::where('asset_id', $this->selectedAsset)->first();
                    if ($selected_asset) {
                        $selected_asset->status = "In Use";
                        $selected_asset->save();
                    }
                }
                AssignAssetEmp::create([
                    'asset_id' => $this->selectedAsset,
                    'emp_id' => $this->empDetails->emp_id,
                    'manufacturer' => $this->assetDetails->manufacturer,
                    'asset_type' => $this->assetDetails->asset_type,
                    'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                    'department' => $this->empDetails->job_role,
                    'asset_assign_file_path' => !empty($mergedFiles) ? json_encode($mergedFiles) : null,
                    'sophos_antivirus' => $this->sophosAntivirus,
                    'vpn_creation' => $this->vpnCreation,
                    'teramind' => $this->teramind,
                    'system_upgradation' => $this->systemUpgradation,
                    'one_drive' => $this->oneDrive,
                    'screenshot_programs' => $this->screenshotPrograms,
                    'mac_address' => $this->macAddress,
                    'laptop_received' => $this->laptopReceived,
                    'is_active' => true,
                ]);

                AssetsHistories::create([
                    'asset_id' => $this->selectedAsset,
                    'assign_or_un_assign' => $this->empDetails->emp_id,
                    'created_by' =>  $emp_id,
                    'action' => 'assign',
                    'status' => 'In Use'
                ]);


                FlashMessageHelper::flashSuccess("Asset assigned to employee successfully!");
            }

            $this->resetForm();

            return redirect()->route('employeeAssetList');
        } catch (\Exception $e) {

            dd('Error while assigning asset: ' . $e->getMessage());

            Log::error('Error while assigning asset: ' . $e->getMessage());

            FlashMessageHelper::flashError("An error occurred while saving the details. Please try again!");
        }
    }



    public function filter()
    {
        try {
            $trimmedEmpId = trim($this->searchEmp);

            $this->filteredEmployeeAssets = AssignAssetEmp::query()
                ->when($trimmedEmpId, function ($query) use ($trimmedEmpId) {
                    $query->where(function ($query) use ($trimmedEmpId) {
                        $query->where('emp_id', 'like', '%' . $trimmedEmpId . '%')
                            ->orWhere('employee_name', 'like', '%' . $trimmedEmpId . '%')
                            ->orWhere('asset_id', 'like', '%' . $trimmedEmpId . '%')
                            ->orWhere('manufacturer', 'like', '%' . $trimmedEmpId . '%')
                            ->orWhere('asset_type', 'like', '%' . $trimmedEmpId . '%')
                            ->orWhere('department', 'like', '%' . $trimmedEmpId . '%');
                    });
                })

                ->get();

            $this->assetsFound = count($this->filteredEmployeeAssets) > 0;
        } catch (\Exception $e) {
            Log::error('Error in filter method: ' . $e->getMessage());
        }
    }

    // Define the updateSearch method if you need it
    public function updateSearch()
    {
        $this->filter();
    }

    public function clearFilters()
    {
        // Reset search fields and filtered results
        // $this->searchEmp = '';
        // $this->searchAssetId = '';
        $this->reset();
        $this->filteredEmployeeAssets = [];
        $this->assetsFound = false;
    }

    public $recordId;
    public $reason = [];

    public function confirmDelete($id, $asset_id)
    {
        // dd($asset_id);
        $this->recordId = $id;
        $this->deleteAsset_id = $asset_id;
        $this->showLogoutModal = true;
        $this->resetErrorBag();
    }

    public function validatefield($field)
    {

        $this->validateOnly($field, $this->rules);
    }

    public $deletionDate;
    public function delete()
    {
        $this->validate($this->rules);

        try {
            // Validate the reason input

            $this->resetErrorBag();

            // Find the AssignAssetEmp record by the provided ID
            $vendormember = AssignAssetEmp::find($this->recordId);
            $vendorAsset = VendorAsset::where('asset_id', $this->deleteAsset_id)->first();
            $assigned_emp_id = $vendormember->emp_id;

            $emp_id = auth()->guard('it')->user()->emp_id;
            // dd( $this->selectedStatus);
            if ($vendorAsset) {
                $vendorAsset->status = $this->selectedStatus;
                $vendorAsset->save();
            }
            // dd($vendorAsset);
            if ($vendormember) {
                // Update the record with delete reason, deactivation, and timestamp
                $vendormember->update([
                    'delete_reason' => $this->reason,
                    'deleted_at' => now(),
                    'is_active' => 0,
                    'status' => $this->selectedAsset,
                ]);

                AssetsHistories::create([
                    'asset_id' => $this->deleteAsset_id,
                    'assign_or_un_assign' => $assigned_emp_id,
                    'created_by' =>  $emp_id,
                    'action' => 'un_assign',
                    'status' => $this->selectedStatus,

                ]);
                // Success flash message
                FlashMessageHelper::flashSuccess("Asset deactivated successfully!");
                $employeeAssetLists = AssignAssetEmp::where('is_active', 1)->get();

                // Reset modal state and input values
                $this->showLogoutModal = false;
                $this->recordId = null;
                $this->reason = '';

                // Fetch updated employee asset list
                $employeeAssetLists = AssignAssetEmp::where('is_active', 1)->get();

                // Return updated view
                return view('livewire.assign-asset-employee', compact('employeeAssetLists'));
            } else {
                // If the record does not exist, log an error and show a message
                Log::error("AssignAssetEmp record not found", ['record_id' => $this->recordId]);
                FlashMessageHelper::flashError('Asset not found.');
            }
        } catch (\Exception $e) {
            // Catch any exceptions and log the error
            Log::error("Error during asset deactivation: " . $e->getMessage(), [
                'exception' => $e,
                'record_id' => $this->recordId,
                'reason' => $this->reason,
            ]);

            // Flash error message for the user
            FlashMessageHelper::flashError('An error occurred while deactivating the asset.');

            // Optionally reset or set any default values
            $this->reset(['reason', 'recordId']);
        }
    }

    public function cancel()
    {
        $this->searchFilters = true;
        $this->showOldEMployeeAssetBtn = true;
        $this->showEMployeeAssetBtn = false;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = true;

        $this->showAssignAssetBtn = true;
        $this->showLogoutModal = false;
        $this->resetErrorBag();
    }

    public $oldEmployeeAssetLists;
    public $sortColumn = 'emp_id'; // default sorting column
    public $sortDirection = 'asc'; // default sorting direction

    public function toggleSortOrder($column)
    {
        try {

            if ($this->sortColumn == $column) {
                // If the column is the same, toggle the sort direction
                $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                // If a different column is clicked, set it as the new sort column and default to ascending order
                $this->sortColumn = $column;
                $this->sortDirection = 'asc';
            }
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error in toggleSortOrder: ' . $e->getMessage());

            // Optionally, set default sort direction or handle the error gracefully
            $this->sortColumn = 'emp_id'; // Example default sort column
            $this->sortDirection = 'asc'; // Example default sort direction

            // You may want to display an error message to the user, if needed
            session()->flash('error', 'An error occurred while changing the sort order.');
        }
    }

    public $allAssetDetails;
    public $allActiveAssetDetails;
    public function render()
    {
        try {

            $this->allAssetDetails = AssignAssetEmp::query()
            ->where('is_active', 0)
            ->get();

            $this->allActiveAssetDetails = AssignAssetEmp::query()
            ->where('is_active', 1)
            ->get();


            $assetTypes = asset_types_table::pluck('asset_names', 'id');
            $employeeAssetLists = !empty($this->filteredEmployeeAssets)
                ? $this->filteredEmployeeAssets
                : AssignAssetEmp::with(['vendorAsset.vendor'])
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->get();

            $employeeAssetLists = $employeeAssetLists->map(function ($employeeAssetList) use ($assetTypes) {
                $employeeAssetList['asset_type_name'] = $assetTypes[$employeeAssetList['asset_type']] ?? 'N/A';
                return $employeeAssetList; // Return the entire modified array
            });

            // Load assets and employees
            $this->loadAssets();
            $this->loadAssetsAndEmployees();
            return view('livewire.assign-asset-employee', compact('employeeAssetLists'));
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred while rendering the AssignAssetEmployee view", [
                'exception' => $e,
                'filteredEmployeeAssets' => $this->filteredEmployeeAssets,
                'sortColumn' => $this->sortColumn,
                'sortDirection' => $this->sortDirection,
            ]);

            FlashMessageHelper::flashError("An error occurred while loading employee assets.");
            return view('livewire.assign-asset-employee', ['employeeAssetLists' => collect()]);
        }
    }
}
