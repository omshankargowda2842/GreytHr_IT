<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\IT;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Vendors extends Component
{
    use WithFileUploads;
    public $vendorName;
    public $contactName;
    public $phone;
    public $gst;
    public $bankName;
    public $accountNumber;
    public $ifscCode;
    public $branch;
    public $contactEmail;
    public $street;
    public $city;
    public $state;
    public $pinCode;
    public $noteDescription;
    public $file_paths = [];
    public $selectedVendorId;
    public $showViewImageDialog = false;
    public $showViewFileDialog = false;


    // Editing mode
    public $editMode = false;
    public $showAddVendor = false;
    public $loading = false;
    public $showLogoutModal = false;
    public $showEditDeleteVendor = true;
    public $vendors ;
    public $reason =[];
    public $existingFilePaths = [];

    protected function rules()
{
    $uniqueGstRule = 'unique:vendors,gst'; // Default rule for creation

    if ($this->editMode) {
        $uniqueGstRule .= ',' . $this->selectedVendorId . ',id'; // For updates, exclude the current record
    }

    return [
        'vendorName' => 'required|string|max:255',
        'contactName' => 'required|string|max:255',
        'phone' => 'required|digits:10',
        'contactEmail' => 'required|email|max:255',
        'gst' => [
            'required',
            'string',
            'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][A-Z0-9][Z][0-9A-Z]$/',
            $uniqueGstRule,
        ],
    ];
}


    protected $messages = [
        'vendorName.required' => 'Vendor Name is required.',
        'vendorName.string' => 'Vendor Name must be a string.',
        'vendorName.max' => 'Vendor Name may not be greater than 255 characters.',

        'contactName.required' => 'Contact Name is required.',
        'contactName.string' => 'Contact Name must be a string.',
        'contactName.max' => 'Contact Name may not be greater than 255 characters.',

        'phone.required' => 'Phone number is required.',
        'phone.string' => 'Phone number must be a number.',
        'phone.max' => 'Phone number may not be greater than 10 characters.',
        'gst.required' => 'The GSTIN field is required.',
        'gst.regex' => 'The GST number format is invalid.',
        'contactEmail.required' => 'Contact Email is required.',
        'contactEmail.email' => 'Contact Email must be a valid email address.',
        'contactEmail.max' => 'Contact Email may not be greater than 255 characters.',
    ];

    public function validateAccountNumber()
    {

        $this->validateOnly('accountNumber');
    }



    private function resetForm()
{
    // Reset fields related to Vendor model
    $this->vendorName = '';
    $this->contactName = '';
    $this->phone = '';
    $this->gst = '';
    $this->bankName = '';
    $this->accountNumber = '';
    $this->ifscCode = '';
    $this->branch = '';
    $this->contactEmail = '';
    $this->street = '';
    $this->city = '';
    $this->state = '';
    $this->pinCode = '';
    $this->noteDescription = '';
    $this->file_paths='';
    $this->reason='';
    $this->reason=null;
    $this->selectedVendorId; // Update this to the correct field name for the selected Vendor
    $this->editMode = false;
    $this->showAddVendor = false; // Update this to match your field name
    $this->showEditDeleteVendor = true; // Update this to match your field name
}

public $files = []; // Store files array
public $selectedFile; // Store the selected file's data

public $showViewVendorDialog = false;
public $currentVendorId = null;


public function showViewVendor($vendorId)
{
    $this->currentVendorId = $vendorId;
    $this->showViewVendorDialog = true;
    $this->showEditDeleteVendor = false;
    $this->editMode = false;
}

public function closeViewVendor()
{
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


    public function showAddVendorMember()
    {
        $this->resetForm();
        $this->showAddVendor = true;
        $this->showEditDeleteVendor = false;
        $this->editMode = false;
    }

    public function cancel(){
        $this->showAddVendor = false;
        $this->editMode = false;
        $this->showEditDeleteVendor = true;
        $this->showLogoutModal = false;
        $this->resetErrorBag();

    }

    public function updatedVendorName()
{
    $this->validateOnly('vendorName');
}
public function updatedContactName()
{
    $this->validateOnly('contactName');
}

public function updatedGst()
{
    $this->validateOnly('gst');
}

public function updatedContactEmail()
{
    $this->validateOnly('contactEmail');
}

public function delete()
{
    $this->validate([
        'reason' => 'required|string|max:255', // Validate the remark input
    ], [
        'reason.required' => 'Reason is required.',
    ]);

    try {


        $vendormember = Vendor::find($this->recordId);

        if ($vendormember) {
            // Update vendor details to deactivate
            $vendormember->update([
                'delete_vendor_reason' => $this->reason,
                'is_active' => 0
            ]);

            FlashMessageHelper::flashSuccess("Vendor deactivated successfully!");
            $this->showLogoutModal = false;

            // Refresh vendor list
            $this->vendors = Vendor::where('is_active', 1)->get();
            $this->recordId = null;
            $this->reason = '';
        }
    } catch (\Exception $e) {
        // Log the exception message
        Log::error('Error in delete method: ' . $e->getMessage());

        // Optionally, display an error message to the user
        FlashMessageHelper::flashError('An error occurred while deactivating the vendor. Please try again later.');
    }
}


public function showEditVendor($id)
{
    try {
        $this->resetForm();
        $this->resetErrorBag();

        // Fetch the vendor record by ID
        $vendor = Vendor::find($id);
        if ($vendor) {
            // Populate the form fields with the vendor's data
            $this->selectedVendorId = $id;
            $this->vendorName = $vendor->vendor_name;
            $this->contactName = $vendor->contact_name;
            $this->phone = $vendor->phone;
            $this->gst = $vendor->gst;
            $this->bankName = $vendor->bank_name;
            $this->accountNumber = $vendor->account_number;
            $this->ifscCode = $vendor->ifsc_code;
            $this->branch = $vendor->branch;
            $this->contactEmail = $vendor->contact_email;
            $this->street = $vendor->street;
            $this->city = $vendor->city;
            $this->state = $vendor->state;
            $this->pinCode = $vendor->pin_code;
            $this->noteDescription = $vendor->description;
            $this->existingFilePaths = json_decode($vendor->file_paths, true) ?? [];

            // Optionally, call a method to update the pin code or perform other operations
            $this->updatedPinCode($this->pinCode);
            // Show the vendor edit form
            $this->showAddVendor = true;
            $this->showEditDeleteVendor = false;
            $this->editMode = true;
        }
    } catch (\Exception $e) {
        // Log the exception message
        Log::error('Error in showEditVendor method: ' . $e->getMessage());

        // Optionally, display an error message to the user
        FlashMessageHelper::flashError('An error occurred while loading the vendor details. Please try again later.');
    }
}

public function downloadImages($vendorId)
{
    try {

    $vendor = collect($this->vendors)->firstWhere('id', $vendorId);

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


    } catch (\Exception $e) {
        // Log the exception message
        Log::error('Error in downloadImages method: ' . $e->getMessage());

        // Optionally, display an error message to the user
        return response()->json(['message' => 'An error occurred while processing the images. Please try again later.'], 500);
    }
    }


    public function submit()
    {
        $this->validate();


        $fileDataArray = [];

        if ($this->editMode) {
            // Fetch the existing vendor record
            $vendor = Vendor::find($this->selectedVendorId);

            if ($vendor) {

                // Retrieve and decode existing file paths
                $existingFileData = json_decode($vendor->file_paths, true);

                // Ensure existing file data is an array
                $existingFileData = is_array($existingFileData) ? $existingFileData : [];

                // If new files are uploaded, replace the existing ones
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



    try {
        if ($this->editMode) {
            // Update existing record
            $vendor = Vendor::find($this->selectedVendorId);
            if ($vendor) {

                $vendor->update([
                    'vendor_name' => $this->vendorName,
                    'contact_name' => $this->contactName,
                    'phone' => $this->phone,
                    'gst' => $this->gst,
                    'bank_name' => $this->bankName,
                    'account_number' => $this->accountNumber,
                    'ifsc_code' => $this->ifscCode,
                    'branch' => $this->branch,
                    'contact_email' => $this->contactEmail,
                    'street' => $this->street,
                    'city' => $this->city,
                    'state' => $this->state,
                    'pin_code' => $this->pinCode,
                    'description' => $this->noteDescription,
                    'file_paths' => json_encode($fileDataArray),
                ]);
                FlashMessageHelper::flashSuccess("Vendor updated successfully!");
            }
        } else {

            // Create new record
            Vendor::create([

                'vendor_name' => $this->vendorName,
                'contact_name' => $this->contactName,
                'phone' => $this->phone,
                'gst' => $this->gst,
                'bank_name' => $this->bankName,
                'account_number' => $this->accountNumber,
                'ifsc_code' => $this->ifscCode,
                'branch' => $this->branch,
                'contact_email' => $this->contactEmail,
                'street' => $this->street,
                'city' => $this->city,
                'state' => $this->state,
                'pin_code' => $this->pinCode,
                'description' => $this->noteDescription,
                'file_paths' => json_encode($fileDataArray),
            ]);
            FlashMessageHelper::flashSuccess("Vendor created successfully!");
        }

        $this->reset();
    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Database error:', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'sql' => $e->getSql(), // Log the SQL query if possible
            'bindings' => $e->getBindings(), // Log the bindings for the SQL query
        ]);
            // Other database exceptions
            Log::error('Database error:', ['error' => $e->getMessage()]);
            FlashMessageHelper::flashError("An error occurred. Please try again!");

    } catch (\Exception $e) {
        Log::error('Error:', ['error' => $e->getMessage()]);
        FlashMessageHelper::flashError("An unexpected error occurred!");
    }
    }

    public $recordId;
    public function confirmDelete($id)
    {
        $this->recordId = $id;

        $this->showLogoutModal = true;
    }

    public $states=[];

    public function mount()
    {

        $this->vendors =  $this->filter();

    }
    public $mandal='';
    public $postOffices = []; // To store PostOffice data for dropdown
    public function updatedPinCode($pinCode)
    {
        try {
            if (strlen($pinCode) === 6) { // Validate pin code length
                $locationData = $this->getLocationFromIndiaPost($pinCode);

                if ($locationData) {
                    // Set the dropdown options and district/state automatically
                    $this->postOffices = $locationData['postOffices'];
                    $this->city = $locationData['city'];
                    $this->state = $locationData['state'];
                } else {
                    // Reset if no data found
                    $this->resetLocationData();
                }
            }
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('Error in updatedPinCode method: ' . $e->getMessage());

            // Optionally, reset location data in case of an error
            $this->resetLocationData();

            // You can also show a user-friendly message
            session()->flash('error', 'An error occurred while retrieving location data. Please try again.');
        }
    }


protected function resetLocationData()
{
    $this->postOffices = [];
    $this->street = null;
    $this->city = '';
    $this->state = '';
}

public function getLocationFromIndiaPost($pinCode)
{
    try {
        // India Post API endpoint
        $url = "https://api.postalpincode.in/pincode/{$pinCode}";
        $response = Http::get($url);

        // Check if the response is successful
        if ($response->successful()) {
            $locationData = $response->json();

            if (isset($locationData[0]['PostOffice']) && !empty($locationData[0]['PostOffice'])) {
                $postOffices = array_map(function($office) {
                    return [
                        'name' => $office['Name'] ?? '',
                        'mandal' => $office['Block'] ?? ''
                    ];
                }, $locationData[0]['PostOffice']);

                return [
                    'postOffices' => $postOffices,
                    'city' => $locationData[0]['PostOffice'][0]['District'] ?? '',
                    'state' => $locationData[0]['PostOffice'][0]['State'] ?? ''
                ];
            }
        }

        // Return null if no data found or if the response is not successful
        return null;

        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('Error fetching location data: ' . $e->getMessage());

            // Optionally, you can return null or an empty array if an error occurs
            return null;
        }
    }


    public $filteredVendor = [];
    public $assetsFound = false;
    public $searchFilters = true;
    public $searchVendor = '';
    public $searchContactName = '';


    public function filter()
    {
        try {
            // Trim the search input
            $trimmedEmpId = trim($this->searchVendor);

            // Start the query for filtering and sorting
            $query = Vendor::query();
            $query->where('is_active', 1);

            // Apply filtering if there's a search term
            if ($trimmedEmpId) {
                $query->where(function ($query) use ($trimmedEmpId) {
                    $query->where('vendor_id', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('vendor_name', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('contact_name', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('gst', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('contact_email', 'like', '%' . $trimmedEmpId . '%');
                });
            }

            // Apply sorting based on selected column and direction
            $query->orderBy($this->sortColumn, $this->sortDirection);

            // Execute the query and get the results
            return $query->get();

        } catch (\Exception $e) {
            // Log the error message for debugging purposes
            Log::error('Error during vendor filter: ' . $e->getMessage());

            // Optionally, return an empty collection or null to indicate an error
            return collect(); // Returning an empty collection
        }
    }



    public function clearFilters()
    {
        // Reset search fields and filtered results
        // $this->searchVendor = '';
        // $this->searchContactName = '';
        $this->reset();
        $this->filteredVendor = [];
        $this->assetsFound = false;

    }

    public $phoneError = '';
    public function updatedPhone($value,$field)
{

    // Check if the phone number starts with an allowed digit (6-9) and has up to 10 digits
    if (!preg_match('/^[6-9][0-9]{0,9}$/', $value)) {
        $this->phoneError = 'Phone number must start with 6, 7, 8, or 9 and be up to 10 digits.';
    } else {
        $this->phoneError = ''; // Clear error message if valid
    }
    $this->resetErrorBag($field);
}

    public function resetValidationForField($field)
    {
        // Reset error for the specific field when typing
        $this->resetErrorBag($field);
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
        // Attempt to filter vendors
        $this->vendors = $this->filter();
        return view('livewire.vendors');
    } catch (\Exception $e) {
        // Log the error
        Log::error('Error rendering vendors: ' . $e->getMessage());
        $this->vendors = [];
        session()->flash('error', 'An error occurred while fetching vendor data.');
        return view('livewire.vendors');
    }
}

}
