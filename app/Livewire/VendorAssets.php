<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\asset_types_table;
use App\Models\Vendor;
use App\Models\VendorAsset;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Picqer\Barcode\BarcodeGeneratorPNG;
class VendorAssets extends Component
{
    use WithFileUploads;
    public $quantity;
    public $assetNames;
    public $assetTypeSearch = '';
    public $assetTypeName = '';
    public $filteredAssetTypes = [];
    public $assetType;
    public $assetModel;
    public $assetSpecification;
    public $color;
    public $barcode;
    public $version;
    public $serialNumber;
    public $invoiceNumber;
    public $taxableAmount;
    public $invoiceAmount;
    public $gstState;
    public $gstCentral;
    public $gstIg;
    public $manufacturer;
    public $purchaseDate;
    public $file_paths = [];
    public $existingFilePaths = [];
    public $showEditDeleteVendor = true;
    public $vendorAssets ;
    public $selectedAssetId;
    public $showAddVendor = false;
    public $editMode = false;
    public $showViewImageDialog = false;
    public $showViewFileDialog = false;
    public $currentVendorId = null;
    public $selectedVendorId;
    public $showLogoutModal = false;
    public $restoreModal = false;
    public $reason =[];

    protected function rules(): array
    {
        $rules = [
            'assetType' => 'required|numeric|max:255',

            'assetModel' => 'required|string|max:255',
            'assetSpecification' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:50',

            'invoiceNumber' => 'required|string|max:255', // Ensure this is required
            'taxableAmount' => 'required|numeric|min:0', // Ensure this is required
            'invoiceAmount' => 'required|numeric|min:0', // Ensure this is required
            'gstIg' => 'nullable|numeric|min:0',
            'gstState' => 'required_if:gstIg,null|string|max:255',
            'gstCentral' => 'required_if:gstIg,null|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'selectedVendorId' => 'required|string|max:255',
           'purchaseDate' => 'required|date|before_or_equal:today',
            'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:40960',
        ];


        if ($this->quantity === null || $this->quantity < 1) {
            $rules['quantity'] = 'required|integer|min:1';  // Ensure it's required if missing or invalid
        }

        // Conditional rule for 'serialNumber' based on 'quantity'
        if ($this->quantity == 1) {
            $rules['serialNumber'] = 'required|string|max:255|unique:vendor_assets,serial_number';
        } else {
            $rules['serialNumber'] = 'nullable|string|max:255'; // No need for serial number if quantity > 1
        }

        if (!$this->editMode) {
            $rules['quantity'] = 'required|integer|min:1';
        }



        if ($this->editMode) {
            $rules['serialNumber'] = [
                'required',
                'string',
                'max:255',
                'unique:vendor_assets,serial_number,' . $this->selectedAssetId . ',id',
            ];
        }

        return $rules;
    }





    protected $messages = [
        'selectedVendorId.required' => 'Vendor is required.',
        'quantity.required' => 'Quantity is required.',

        'assetType.required' => 'Asset Type is required.',

        'assetModel.required' => 'Asset Model is required.',
        'assetModel.string' => 'Asset Model must be a string.',
        'assetModel.max' => 'Asset Model may not be greater than 255 characters.',

        'assetSpecification.required' => 'Asset Specification is required.',
        'assetSpecification.string' => 'Asset Specification must be a string.',
        'assetSpecification.max' => 'Asset Specification may not be greater than 255 characters.',

        'color.string' => 'Color must be a string.',
        'color.max' => 'Color may not be greater than 50 characters.',

        'version.string' => 'Version must be a string.',
        'version.max' => 'Version may not be greater than 50 characters.',

        'serialNumber.required' => 'Serial Number is required.',
        'serialNumber.string' => 'Serial Number must be a string.',
        // 'serialNumber.unique' => 'Serial Number has already been taken.',
        'serialNumber.max' => 'Serial Number may not be greater than 255 characters.',

        'invoiceNumber.required' => 'Invoice Number is required.',
        'invoiceNumber.string' => 'Invoice Number must be a string.',
        'invoiceNumber.max' => 'Invoice Number may not be greater than 255 characters.',

        'gstState.required' => 'GST State is required.',
        'gstState.string' => 'GST State must be a string.',
        'gstState.max' => 'GST State may not be greater than 255 characters.',

        'gstCentral.required' => 'GST Central is required.',
        'gstCentral.string' => 'GST Central must be a string.',
        'gstCentral.max' => 'GST Central may not be greater than 255 characters.',

        'taxableAmount.required' => 'Taxable Amount is required.',
        'taxableAmount.numeric' => 'Taxable Amount must be a number.',
        'taxableAmount.min' => 'Taxable Amount must be at least 0.',

        'invoiceAmount.required' => 'Invoice Amount is required.',
        'invoiceAmount.numeric' => 'Invoice Amount must be a number.',
        'invoiceAmount.min' => 'Invoice Amount must be at least 0.',

        'purchaseDate.required' => 'Purchase Date is required.',
         'purchaseDate.date' => 'Purchase Date must be a valid date.',
        'purchaseDate.before_or_equal' => 'Purchase Date cannot be a future date.',

        'manufacturer.required' => 'Manufacturer is required.',
        'manufacturer.string' => 'Manufacturer must be a string.',
        'manufacturer.max' => 'Manufacturer may not be greater than 255 characters.',



    ];

    public function handleAssetTypeChangeAndResetValidation()
{
    $this->handleAssetTypeChange();
    $this->resetValidationForField('assetType');
}


    public function handleAssetTypeChange()
    {

        // Check if 'Others' option is selected
        if ($this->assetType === 'others') {
            // Open the modal when 'Others' is selected
            $this->showModal();
        }
    }


    public function resetValidationForField($field)
    {

        // Reset error for the specific field when typing
        $this->resetErrorBag($field);
    }

    public $vendors;

    public $showViewVendorDialog = false;

    public function showViewVendor($vendorId)
    {
        $this->currentVendorId = $vendorId;
        $this->showViewVendorDialog = true;
        $this->showEditDeleteVendor = false;
        $this->searchFilters = false;
        $this->editMode = false;
    }

    public function closeViewVendor()
    {
        $this->searchFilters = true;
        $this->showViewVendorDialog = false;
        $this->showEditDeleteVendor = true;
        $this->currentVendorId = null;
    }




    public function closeViewFile()
    {
        $this->showViewFileDialog = false;
    }
    public function showViewImage($vendorId)
    {
        $this->currentVendorId = $vendorId;
        $this->showViewImageDialog = true;
    }

    public function showViewFile($vendorId)
    {
        $this->currentVendorId = $vendorId;
        $this->showViewFileDialog = true;
    }

    public function closeViewImage()
    {
        $this->showViewImageDialog = false;
    }

    public function downloadImages($vendorId)
    {
        try {
            $vendor = collect($this->vendorAssets)->firstWhere('id', $vendorId);

            if (!$vendor) {
                // Vendor not found
                return response()->json(['message' => 'Vendor not found'], 404);
            }

            $fileDataArray = is_string($vendor->file_paths)
                ? json_decode($vendor->file_paths, true)
                : $vendor->file_paths;

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


    public function showAddVendorMember()
    {
        $this->resetForm();
        $this->showAddVendor = true;
        $this->showEditDeleteVendor = false;
        $this->editMode = false;
    }



    private function resetForm()
{
    // Reset fields related to Asset model
    $this->selectedVendorId ='';
    $this->assetType = '';
    $this->quantity = '';
    $this->assetModel = '';
    $this->assetSpecification = '';
    $this->color = '';
    $this->version = '';
    $this->serialNumber = '';
    $this->invoiceNumber = '';
    $this->taxableAmount = '';
    $this->invoiceAmount = '';
    $this->gstState = '';
    $this->gstCentral = '';
    $this->gstIg = '';
    $this->manufacturer = '';
    $this->purchaseDate = null;
    $this->file_paths = [];

    $this->selectedAssetId = null; // Reset the selected asset ID
    $this->editMode = false; // Reset edit mode
    $this->showAddVendor = false; // Hide add vendor form
    $this->showEditDeleteVendor = true; // Show edit/delete vendor options
}

    public function cancel(){
        $this->showAddVendor = false;
        $this->editMode = false;
        $this->showEditDeleteVendor = true;
        $this->showLogoutModal = false;
        $this->restoreModal = false;
        $this->recordId = null;
        $this->reason = '';
        $this->resetErrorBag();

    }
    public function delete()
    {
        try {
            $this->validate([

                'reason' => 'required|string|max:255', // Validate the remark input
            ], [
                'reason.required' => 'Reason is required.',
            ]);

            $this->resetErrorBag();

            $vendormember = VendorAsset::find($this->recordId);

            if ($vendormember) {
                // Perform the update (deactivation)
                $vendormember->update([
                    'delete_asset_reason' => $this->reason,
                    'is_active' => 0,
                ]);

                FlashMessageHelper::flashSuccess("Asset deactivated successfully!");
                $this->showLogoutModal = false;

                // Refresh the vendor assets and reset fields
                $this->vendorAssets = VendorAsset::get();
                $this->recordId = null;
                $this->reason = '';
            } else {
                return response()->json(['message' => 'Vendor asset not found'], 404);
            }

        } catch (\Exception $e) {
            // Handle any exception that occurs and return a proper response
            return response()->json([
                'message' => 'An error occurred while deactivating the asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public $recordId;
    public function confirmDelete($id)
    {
        $this->resetErrorBag();
        $this->recordId = $id;
        $this->showLogoutModal = true;
    }

    public function showEditAsset($id)
    {
        $this->resetErrorBag();
        try {
            // Fetch the asset record by ID
            $asset = VendorAsset::find($id);

            if ($asset) {
                $this->selectedAssetId = $id;
                $this->manufacturer = $asset->manufacturer;
                $this->assetType = $asset->asset_type;
                $this->assetModel = $asset->asset_model;
                $this->assetSpecification = $asset->asset_specification;
                $this->color = $asset->color;
                $this->version = $asset->version;
                $this->serialNumber = $asset->serial_number;
                $this->invoiceNumber = $asset->invoice_number;
                $this->taxableAmount = $asset->taxable_amount;
                $this->invoiceAmount = $asset->invoice_amount;
                $this->barcode = $asset->barcode;
                $this->gstState = $asset->gst_state;
                $this->selectedVendorId = $asset->vendor_id;
                $this->gstCentral = $asset->gst_central;
                $this->gstCentral = $asset->gst_ig;
                $this->purchaseDate = $asset->purchase_date ? Carbon::parse($asset->purchase_date)->format('Y-m-d') : null;

                $this->existingFilePaths = json_decode($asset->file_paths, true) ?? [];

                $this->showAddVendor = true;
                $this->showEditDeleteVendor = false;
                $this->editMode = true;
            } else {
                return response()->json(['message' => 'Asset not found'], 404);
            }
        } catch (\Exception $e) {
            // Handle any exception that occurs and return a proper response
            return response()->json([
                'message' => 'An error occurred while fetching the asset details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function restore($id)
    {
        try {
            // Fetch the vendor asset by ID
            $vnrAst = VendorAsset::find($id);

            if ($vnrAst) {
                // Restore the asset by setting is_active to 1
                $vnrAst->is_active = 1;
                $vnrAst->save();

                FlashMessageHelper::flashSuccess("Asset restored successfully!");
                $this->restoreModal = false;
                $this->vendorAssets = VendorAsset::get();
            } else {
                return response()->json(['message' => 'Asset not found'], 404);
            }
        } catch (\Exception $e) {
            // Handle any exception that occurs and return a proper response
            return response()->json([
                'message' => 'An error occurred while restoring the asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


public $vendorAssetIdToRestore;


public function cancelLogout($id)
{
    $this->vendorAssetIdToRestore = $id;
     $this->restoreModal = true;
}

public function updateStatus($vendorAssetId, $newStatus)
{
    $vendorAsset = VendorAsset::find($vendorAssetId);

    if ($vendorAsset) {
        $vendorAsset->status = $newStatus;
        $vendorAsset->save();

        // Optionally, you can emit an event to notify the UI or log actions.
        FlashMessageHelper::flashSuccess("Vendor status updated successfully.");
     
    }
}


public function submit()
{

    $this->validate($this->rules());



    try {

        $barcodeBase64 = null;
        if (!empty($this->serialNumber)) {

    $generator = new BarcodeGeneratorPNG();

    $barcode = $generator->getBarcode($this->serialNumber, $generator::TYPE_CODE_128);


    $barcodeBase64 = base64_encode($barcode);

        }
    $fileDataArray = [];

    if ($this->editMode) {
        // Fetch the existing vendor record
        $vendorAst = VendorAsset::find($this->selectedAssetId);

        if ($vendorAst) {

            // Retrieve and decode existing file paths
            $existingFileData = json_decode($vendorAst->file_paths, true);

            // Ensure existing file data is an array
            $existingFileData = is_array($existingFileData) ? $existingFileData : [];

            // If new files are uploaded, replace the existing ones
            if ($this->file_paths) {
                $this->validate([
                    'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:40960',
                ]);

                foreach ($this->file_paths as $file) {

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

                }
            } else {
                // No new files provided, keep the existing files
                $fileDataArray = $existingFileData;
            }
        }
    } else {
        // New record creation
        if ($this->file_paths) {
            $this->validate([
                'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:40960',
            ]);

            foreach ($this->file_paths as $file) {
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
    }



    if ($this->editMode) {
        // Update existing asset record
        $asset = VendorAsset::find($this->selectedAssetId);

        if ($asset) {

            $asset->update([
                'vendor_id' => $this->selectedVendorId,
                'manufacturer' => $this->manufacturer,
                'asset_type' => $this->assetType,
                'asset_model' => $this->assetModel,
                'asset_specification' => $this->assetSpecification,
                'color' => $this->color,
                'version' => $this->version,
                'serial_number' => $this->serialNumber,
                'invoice_number' => $this->invoiceNumber,
                'taxable_amount' => $this->taxableAmount,
                'invoice_amount' => $this->invoiceAmount,
                'barcode' => $barcodeBase64,
                'gst_state' => $this->gstState,
                'gst_central' => $this->gstCentral,
                'gst_ig' => $this->gstIg,
                'purchase_date' => $this->purchaseDate ? $this->purchaseDate : null,
                'file_paths' => json_encode($fileDataArray),
            ]);
            FlashMessageHelper::flashSuccess("Asset updated successfully!");
        }
    } else {

        // Create new asset record
        for ($i = 0; $i < $this->quantity; $i++) {
        VendorAsset::create([
            'vendor_id' => $this->selectedVendorId,
            'manufacturer' => $this->manufacturer,
            'asset_type' => $this->assetType,
            'asset_model' => $this->assetModel,
            'asset_specification' => $this->assetSpecification,
            'color' => $this->color,
            'version' => $this->version,
            'serial_number' => $this->serialNumber,
            'invoice_number' => $this->invoiceNumber,
            'taxable_amount' => $this->taxableAmount,
            'invoice_amount' => $this->invoiceAmount,
            'barcode' => $barcodeBase64,
            'gst_state' => $this->gstState,
            'gst_central' => $this->gstCentral,
            'gst_ig' => $this->gstIg,
            'purchase_date' => $this->purchaseDate ? $this->purchaseDate : null,
            'file_paths' => json_encode($fileDataArray),
        ]);

    }
    FlashMessageHelper::flashSuccess("Asset created successfully!");
    }

            $this->reset();

    }
     catch (\Exception $e) {
            // Handle the exception and log the error
            Log::error('Error during form submission:', ['error' => $e->getMessage()]);
            FlashMessageHelper::flashError("An error occurred during submission. Please try again later!");
        }

}

public $newAssetName;
public $isModalOpen = false;
public function showModal()
    {

        $this->isModalOpen = true; // Open modal
    }

    public function closeModal()
    {
        $this->resetErrorBag(); // Clears validation errors
        $this->isModalOpen = false; // Close modal
    }

    public function createAssetType()
    {
        try {
            // Validate if needed (uncomment and adjust validation if necessary)
            // $this->validate([
            //     'newAssetName' => 'required|string|max:255|unique:asset_types_tables,asset_names',
            // ]);

            // Create the new asset type
            asset_types_table::create([
                'asset_names' => $this->newAssetName,
            ]);

            // Refresh the asset types list
            $this->assetNames = asset_types_table::orderBy('asset_names', 'asc')->get();

            // Reset the form
            $this->newAssetName = '';

            // Close the modal
            $this->closeModal();

            // Optionally, flash a success message
            FlashMessageHelper::flashSuccess("Asset type created successfully!");

        } catch (\Exception $e) {
            // Handle any exception that occurs and return a proper response
            return response()->json([
                'message' => 'An error occurred while creating the asset type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function mount()
    {
        try {
            // Attempt to fetch asset names from the database
            $this->assetNames = asset_types_table::orderBy('asset_names', 'asc')->get();
        } catch (\Exception $e) {

            Log::error('Error fetching asset names: ' . $e->getMessage());
            $this->assetNames = collect();  // Set an empty collection in case of an error

            // Optionally, you can flash an error message to the user
            FlashMessageHelper::flashError('An error occurred while loading asset names.');
        }
    }


    public $filteredVendorAssets = [];
    public $assetsFound = false;
    public $searchFilters = true;
    public $searchEmp = '';
    public $searchAssetId = '';


    public function filter()
{
    try {
    $trimmedEmpId = trim($this->searchEmp); // Trimmed search input

    return VendorAsset::with('vendor') // Eager load the 'vendor' relationship
        ->whereHas('vendor', function ($query) {
            $query->where('is_active', 1); // Ensure the vendor is active
        })
        ->when($trimmedEmpId, function ($query) use ($trimmedEmpId) {
            // Apply the search filters based on input
            $query->where(function ($query) use ($trimmedEmpId) {
                $query->where('vendor_id', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhereHas('vendor', function ($query) use ($trimmedEmpId) {
                        // Search within vendor fields as well
                        $query->where('vendor_id', 'like', '%' . $trimmedEmpId . '%')
                            ->orWhere('vendor_name', 'like', '%' . $trimmedEmpId . '%')
                            ->orWhere('contact_email', 'like', '%' . $trimmedEmpId . '%');
                    })
                    ->orWhere('asset_id', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhere('manufacturer', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhere('asset_type', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhere('invoice_number', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhere('serial_number', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhere('is_active', 'like', '%' . $trimmedEmpId . '%');
            });
        })
        ->orderBy($this->sortColumn, $this->sortDirection) // Apply sorting
        ->get();
    }
    catch (\Exception $e) {
        Log::error('Error in filter method: ' . $e->getMessage());
    }

}


    public function updated($propertyName)
    {
        // Trigger calculation when any of these fields are updated
        if (in_array($propertyName, ['gstIg', 'gstState', 'gstCentral', 'taxableAmount'])) {
            $this->calculateInvoiceAmount();
        }
    }

    public function calculateInvoiceAmount()
    {
        // Ensure inputs are numeric to avoid errors
        $gstIg = is_numeric($this->gstIg) ? (float) $this->gstIg : 0;
        $gstState = is_numeric($this->gstState) ? (float) $this->gstState : 0;
        $gstCentral = is_numeric($this->gstCentral) ? (float) $this->gstCentral : 0;
        $taxableAmount = is_numeric($this->taxableAmount) ? (float) $this->taxableAmount : 0;

        // If IGST is entered, we sum it with the taxable amount and update invoice amount
        if ($gstIg > 0) {
            $this->invoiceAmount = $gstIg + $taxableAmount;
        } else {
            // If IGST is not provided, calculate based on state and central GST
            $this->invoiceAmount = $gstState + $gstCentral + $taxableAmount;
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
        $this->filteredVendorAssets = [];
        $this->assetsFound = false;

    }

    public $sortColumn = 'vendor_id'; // default sorting column
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
        $this->sortColumn = 'vendor_id'; // Example default sort column
        $this->sortDirection = 'asc'; // Example default sort direction

        // You may want to display an error message to the user, if needed
        session()->flash('error', 'An error occurred while changing the sort order.');
    }
    }



    public function render()
    {
        try {
            // Fetch asset names ordered by asset_names
            $this->assetNames = asset_types_table::orderBy('asset_names', 'asc')->get();
            // Fetch asset types with their ids for mapping
            $assetTypes = asset_types_table::pluck('asset_names', 'id');

            // Apply any filters to the vendor assets
            $this->vendorAssets = $this->filter();

            // Map the asset type name to each vendor asset
            $this->vendorAssets = $this->vendorAssets->map(function ($vendorAsset) use ($assetTypes) {
                $vendorAsset['asset_type_name'] = $assetTypes[$vendorAsset['asset_type']] ?? 'N/A';
                return $vendorAsset;
            });

            // Fetch all vendors
            $this->vendors = Vendor::all();

            // Return the view with filtered asset types
            return view('livewire.vendor-assets', [
                'filteredAssetTypes' => $this->filteredAssetTypes,
            ]);

        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            // Optionally, log the error
            Log::error('Error in rendering vendor assets: ' . $e->getMessage());

            // Optionally, flash an error message to the user
            FlashMessageHelper::flashError('An error occurred while rendering the vendor assets.');

            // You can also return a fallback view or empty data
            return view('livewire.vendor-assets', [
                'filteredAssetTypes' => collect(), // Provide an empty collection or default data
            ]);
        }
    }

}
