<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;
use App\Models\Borrowing;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;
use Livewire\WithPagination;
use Carbon\Carbon;

class BorrowingHistoryTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $status = '';
    public $department = '';
    public $startDate = '';
    public $endDate = '';
    public $sortField = 'borrow_date';
    public $sortDirection = 'desc';

    protected $borrowingRepository;

    // Define queryString property for URL parameter tracking
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'department' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'sortField' => ['except' => 'borrow_date'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function mount($status = null)
    {
        if ($status) {
            $this->status = $status;
        }
    }

    // Reset pagination when filters change
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedStatus()
    {
        $this->resetPage();
    }
    public function updatedDepartment()
    {
        $this->resetPage();
    }
    public function updatedStartDate()
    {
        $this->resetPage();
    }
    public function updatedEndDate()
    {
        $this->resetPage();
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->department = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
    }

    // Sort by a given field
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Inject repository using Livewire's boot method
    public function boot(BorrowingRepositoryInterface $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    public function render()
    {
        $departments = Department::all();

        // Format dates for database query if provided
        $formattedStartDate = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $formattedEndDate = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        // Use repository to get filtered borrowings
        $borrowings = $this->borrowingRepository->getAdvancedFilteredBorrowings(
            $this->search,
            $this->status,
            $this->department,
            $formattedStartDate,
            $formattedEndDate,
            $this->sortField,
            $this->sortDirection
        );

        // Calculate overdue days or late return days
        foreach ($borrowings as $borrowing) {
            if ($borrowing->status === Borrowing::STATUS_OVERDUE) {
                $borrowing->days_overdue = $this->calculateBusinessDaysBetween($borrowing->borrow_date, now()) - 7;
                $borrowing->days_overdue = max(0, $borrowing->days_overdue);
            } elseif ($borrowing->status === Borrowing::STATUS_RETURNED && $borrowing->return_date) {
                $borrowing->days_late = $this->calculateBusinessDaysBetween($borrowing->borrow_date, $borrowing->return_date) - 7;
                $borrowing->days_late = max(0, $borrowing->days_late);
            }
        }

        return view('livewire.borrowing-history-table', [
            'borrowings' => $borrowings,
            'departments' => $departments
        ]);
    }

    /**
     * Calculate business days between two dates.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return int
     */
    private function calculateBusinessDaysBetween($startDate, $endDate)
    {
        $days = 0;
        $current = $startDate->copy();

        while ($current->lt($endDate)) {
            $current->addDay();
            // Only count weekdays (1 = Monday, 5 = Friday)
            if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                $days++;
            }
        }

        return $days;
    }
}
