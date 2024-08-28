<?php

namespace App\Livewire;

use App\Models\Vendor;
use App\Models\VendorAsset;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class VendorAssets extends Component
{
    use WithFileUploads;
    public $assetType;
    public $assetModel;
    public $assetSpecification;
    public $color;
    public $version;
    public $serialNumber;
    public $invoiceNumber;
    public $taxableAmount;
    public $invoiceAmount;
    public $gstState;
    public $gstCentral;
    public $manufacturer;
    public $purchaseDate;
    public $file_paths = [];
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

    protected function rules(): array
    {
        $rules = [
            'assetType' => 'required|string|max:255',
            'assetModel' => 'required|string|max:255',
            'assetSpecification' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:50',
            'serialNumber' => 'required|string|max:255',
            'invoiceNumber' => 'nullable|string|max:255',
            'taxableAmount' => 'nullable|numeric|min:0',
            'invoiceAmount' => 'nullable|numeric|min:0',
            'gstState' => 'nullable|string|max:255',
            'gstCentral' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'purchaseDate' => 'nullable|date',
            'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:40960',
        ];

        if ($this->editMode) {
            $rules['serialNumber'] = [
                'required',
                'string',
                'max:255',
                'unique:vendor_assets,serial_number,' . $this->selectedAssetId . ',id',
            ];
        } else {
            $rules['serialNumber'] = 'required|string|max:255|unique:vendor_assets,serial_number';
        }

        return $rules;
    }





    protected $messages = [
        'assetType.required' => 'Asset Type is required.',
        'assetType.string' => 'Asset Type must be a string.',
        'assetType.max' => 'Asset Type may not be greater than 255 characters.',

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
        'serialNumber.unique' => 'Serial Number has already been taken.',
        'serialNumber.max' => 'Serial Number may not be greater than 255 characters.',

        'invoiceNumber.string' => 'Invoice Number must be a string.',
        'invoiceNumber.max' => 'Invoice Number may not be greater than 255 characters.',

        'taxableAmount.numeric' => 'Taxable Amount must be a number.',
        'taxableAmount.min' => 'Taxable Amount must be at least 0.',

        'invoiceAmount.numeric' => 'Invoice Amount must be a number.',
        'invoiceAmount.min' => 'Invoice Amount must be at least 0.',

        'gstState.string' => 'GST State must be a string.',
        'gstState.max' => 'GST State may not be greater than 255 characters.',

        'gstCentral.string' => 'GST Central must be a string.',
        'gstCentral.max' => 'GST Central may not be greater than 255 characters.',

        'manufacturer.string' => 'Manufacturer must be a string.',
        'manufacturer.max' => 'Manufacturer may not be greater than 255 characters.',

        'purchaseDate.date' => 'Purchase Date must be a valid date.',

        // 'file_paths.*.file' => 'Each file must be a valid file.',
        // 'file_paths.*.mimes' => 'Each file must be a file of type: jpg, jpeg, png, pdf.',
        // 'file_paths.*.max' => 'Each file may not be greater than 2MB.',
    ];

    public $vendors;



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
        fn($fileData) => strpos($fileData['mime_type'], 'image') !== false
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
        $this->resetErrorBag();

    }
    public function delete()

    {   $vendormember = Vendor::find($this->recordId);
        dd($vendormember);
        if ($vendormember) {
            // Permanently delete the record from the database
            $vendormember->delete();

            session()->flash('message', 'Vendor deleted successfully!');
            $this->showLogoutModal = false;

            //Refresh
            $this->vendors = DB::table('vendors')->get();

        }
    }

    public $recordId;
    public function confirmDelete($id)
    {
        $this->recordId = $id;
        $this->showLogoutModal = true;
    }

    public function showEditAsset($id)
{
    // $this->resetForm();
    // $this->resetErrorBag();

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
        $this->gstState = $asset->gst_state;
        $this->selectedVendorId = $asset->vendor_id;
        $this->gstCentral = $asset->gst_central;
        $this->purchaseDate = $asset->purchase_date ? Carbon::parse($asset->purchase_date)->format('Y-m-d') : null;

        // Handle file_paths if exists
        // $this->file_paths = $asset->file_paths ? 'data:file_paths/jpeg;base64,' . base64_encode(file_get_contents(storage_path('app/' . $asset->file_paths))) : null;

        $this->showAddVendor = true;
        $this->showEditDeleteVendor = false;
        $this->editMode = true;
    }
}


public function submit()
{

    $this->validate($this->rules());

    $fileDataArray = [];

        $this->validate([
            'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:40960',
        ]);


    if ($this->file_paths) {
        foreach ($this->file_paths as $file) {
            try {
                if (file_exists($file->getRealPath())) {
                    // $fileContent = $file->get();
                    $fileContent = file_get_contents($file->getRealPath());
                    $mimeType = $file->getMimeType();
                    $base64File = base64_encode($fileContent);

                    $fileDataArray[] = [
                        'data' => $base64File,
                        'mime_type' => $mimeType,
                        'original_name' => $file->getClientOriginalName(),
                    ];

                } else {
                    Log::error('File does not exist:', ['file' => $file->getClientOriginalName()]);
                }
            } catch (\Exception $e) {
                Log::error('Error processing file:', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    } else {
        Log::info('No files received.');
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
                'gst_state' => $this->gstState,
                'gst_central' => $this->gstCentral,
                'purchase_date' => $this->purchaseDate ? $this->purchaseDate : null,
                'file_paths' => json_encode($fileDataArray),
            ]);
        }
    } else {
        // Create new asset record

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
            'gst_state' => $this->gstState,
            'gst_central' => $this->gstCentral,
            'purchase_date' => $this->purchaseDate ? $this->purchaseDate : null,
            'file_paths' => json_encode($fileDataArray),
        ]);
    }

    // Flash success message and reset form
    session()->flash('message', 'Form submitted successfully!');
    $this->reset();
}


    public function render()
    {
        $this->vendors = Vendor::all();
        $this->vendorAssets = DB::table('vendor_assets')->get();
        return view('livewire.vendor-assets');
    }
}