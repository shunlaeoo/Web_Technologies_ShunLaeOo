import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import { vi } from 'vitest';
import Plan from './Plan';
import { toast } from 'react-toastify';
import axios from 'axios';

// Mock dependencies
vi.mock('axios');

// Mock react-toastify
vi.mock('react-toastify', () => ({
  toast: {
    loading: vi.fn(() => 'loading-toast-id'),
    update: vi.fn(),
    success: vi.fn(),
    error: vi.fn()
  }
}));

// Mock react-router-dom's useNavigate
const mockNavigate = vi.fn();
vi.mock('react-router-dom', async () => {
  const actual = await vi.importActual('react-router-dom');
  return {
    ...actual,
    useNavigate: () => mockNavigate
  };
});

describe('Plan Component', () => {
  const mockRef = { current: null };

  const mockWorkoutPlan = [
    {
      exercise: {
        id: 1,
        name: 'Push-ups',
        instructions: 'Keep your body straight',
        video_url: 'https://www.youtube.com/watch?v=IODxDxX7oi4',
        image: 'exercises/pushup.jpg'
      },
      sets: 3,
      reps: 10,
      workout_plan_id: 1
    },
    {
      exercise: {
        id: 2,
        name: 'Squats',
        instructions: 'Keep your back straight',
        video_url: 'https://www.youtube.com/watch?v=YaXPRqUwItQ',
        image: 'exercises/squat.jpg'
      },
      sets: 3,
      reps: 15,
      workout_plan_id: 1
    }
  ];

  const mockMealPlan = {
    protein: 30,
    carbs: 50,
    fats: 20,
    description: '<p>Breakfast: Oatmeal with fruits</p><p>Lunch: Chicken salad</p>'
  };

  beforeEach(() => {
    vi.clearAllMocks();

    // Mock localStorage
    Storage.prototype.getItem = vi.fn(() => 'fake-token');

    // Mock successful API responses
    axios.get.mockImplementation((url) => {
      if (url === 'http://127.0.0.1:8000/api/plans') {
        return Promise.resolve({
          data: {
            exercises: mockWorkoutPlan,
            meal_plan: mockMealPlan,
            daily_calories: 2000
          }
        });
      } else if (url === 'http://127.0.0.1:8000/api/user') {
        return Promise.resolve({
          data: {
            user: { id: 1, name: 'John Doe' },
            completedWorkouts: [2]
          }
        });
      }
    });

    // Mock the POST request for workout completion
    axios.post.mockResolvedValue({ data: { success: true } });
  });

  test('renders workout plan and meal plan after fetching data', async () => {
    render(
      <BrowserRouter>
        <Plan recommendationsRef={mockRef} />
      </BrowserRouter>
    );

    // Wait for the loading toast to be triggered
    await waitFor(() => {
      expect(toast.loading).toHaveBeenCalledWith('Fetching your plan...');
    });

    // Wait for data to load
    await waitFor(() => {
      // Check if workout plan exercises are rendered
      expect(screen.getByText('Push-ups')).toBeInTheDocument();
      expect(screen.getByText('Squats')).toBeInTheDocument();

      // Check if sets and reps are displayed
      expect(screen.getByText('3 × 10')).toBeInTheDocument();
      expect(screen.getByText('3 × 15')).toBeInTheDocument();

      // Check if meal plan data is displayed
      expect(screen.getByText('30%')).toBeInTheDocument(); // Protein
      expect(screen.getByText('50%')).toBeInTheDocument(); // Carbs
      expect(screen.getByText('20%')).toBeInTheDocument(); // Fats
      expect(screen.getByText('2000 kcal')).toBeInTheDocument(); // Daily calories
    });

    // Check if toast was updated
    expect(toast.update).toHaveBeenCalledWith(
      'loading-toast-id',
      expect.objectContaining({
        render: 'Workout plan loaded!',
        type: 'success',
        isLoading: false
      })
    );
  });

  test('handles exercise video modal', async () => {
    render(
      <BrowserRouter>
        <Plan recommendationsRef={mockRef} />
      </BrowserRouter>
    );

    // Wait for data to load
    await waitFor(() => {
      expect(screen.getByText('Push-ups')).toBeInTheDocument();
    });

    // Click the View button for the first exercise
    const viewButtons = screen.getAllByText('View');
    fireEvent.click(viewButtons[0]);

    // Check if modal is opened with correct content
    expect(screen.getByText('Push-ups Tutorial')).toBeInTheDocument();

    // Check if iframe is rendered with correct YouTube URL
    const iframe = screen.getByTitle('Exercise Video');
    expect(iframe).toBeInTheDocument();
    expect(iframe.src).toContain('youtube.com/embed/IODxDxX7oi4');

    // Close the modal
    const closeButton = screen.getByText('×');
    fireEvent.click(closeButton);

    // Check if modal is closed
    await waitFor(() => {
      expect(screen.queryByText('Push-ups Tutorial')).not.toBeInTheDocument();
    });
  });

  test('handles workout completion', async () => {
    // Reset mocks before this specific test
    vi.clearAllMocks();

    // Mock the success toast
    const mockToastSuccess = vi.fn();
    toast.success = mockToastSuccess;

    // Mock the API responses
    axios.get.mockImplementation((url) => {
      if (url === 'http://127.0.0.1:8000/api/plans') {
        return Promise.resolve({
          data: {
            exercises: mockWorkoutPlan,
            meal_plan: mockMealPlan,
            daily_calories: 2000
          }
        });
      } else if (url === 'http://127.0.0.1:8000/api/user') {
        return Promise.resolve({
          data: {
            user: { id: 1, name: 'John Doe' },
            completedWorkouts: [2]
          }
        });
      }
    });

    // Mock the POST request to immediately resolve with success
    axios.post.mockResolvedValue({ data: { success: true } });

    render(
      <BrowserRouter>
        <Plan recommendationsRef={mockRef} />
      </BrowserRouter>
    );

    // Wait for data to load
    await waitFor(() => {
      expect(screen.getByText('Push-ups')).toBeInTheDocument();
    });

    // Check initial state (Squats should be completed, Push-ups should be not completed)
    const completeButtons = screen.getAllByText('Complete');
    expect(completeButtons).toHaveLength(1); // Only Push-ups should be not completed
    expect(screen.getByText('Completed')).toBeInTheDocument(); // Squats should be completed

    // Complete the exercise (Push-ups)
    fireEvent.click(completeButtons[0]);
  });

  test('handles API errors gracefully', async () => {
    // Create a new mock implementation just for this test
    vi.clearAllMocks();

    // Mock the toast.loading function
    toast.loading.mockReturnValueOnce('loading-toast-id');

    // Mock both API calls to fail consistently
    axios.get.mockImplementation((url) => {
      if (url === 'http://127.0.0.1:8000/api/plans') {
        return Promise.reject(new Error('Network error'));
      } else if (url === 'http://127.0.0.1:8000/api/user') {
        return Promise.reject(new Error('Network error'));
      }
      return Promise.reject(new Error(`Unexpected URL: ${url}`));
    });

    render(
      <BrowserRouter>
        <Plan recommendationsRef={mockRef} />
      </BrowserRouter>
    );

    // Check loading state
    await waitFor(() => {
      expect(toast.loading).toHaveBeenCalledWith('Fetching your plan...');
    });

    // Wait for error handling
    await waitFor(() => {
      // Check if error toast was shown
      expect(toast.update).toHaveBeenCalledWith(
        'loading-toast-id',
        expect.objectContaining({
          render: 'Failed to load plan.',
          type: 'error',
          isLoading: false,
          autoClose: 2000
        })
      );
    });
  });
});

