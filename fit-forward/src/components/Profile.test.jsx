import { render, screen, waitFor } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import { vi } from 'vitest';
import Profile from './Profile';
import axios from 'axios';
import { AuthProvider } from '../context/AuthContext';

// Mock dependencies
vi.mock('axios');
vi.mock('./Achievements', () => ({
  default: () => <div data-testid="achievements-component">Achievements Mock</div>
}));
vi.mock('./Header', () => ({
  default: () => <div data-testid="header-component">Header Mock</div>
}));
vi.mock('./Footer', () => ({
  default: () => <div data-testid="footer-component">Footer Mock</div>
}));
vi.mock('recharts', () => ({
  ResponsiveContainer: ({ children }) => <div data-testid="responsive-container">{children}</div>,
  LineChart: ({ children }) => <div data-testid="line-chart">{children}</div>,
  Line: () => <div data-testid="chart-line" />,
  XAxis: () => <div data-testid="x-axis" />,
  YAxis: () => <div data-testid="y-axis" />,
  CartesianGrid: () => <div data-testid="cartesian-grid" />,
  Tooltip: () => <div data-testid="chart-tooltip" />
}));

describe('Profile Component', () => {
  const mockProgressData = {
    greeting: 'Good morning',
    user_name: 'John',
    streak: 5,
    plan: 'Full Body Workout',
    goal: { completed: 2, total: 5 },
    progress: 40,
    weeklyData: [
      { day: 'Mon', workouts: 2, date: '2023-10-01' },
      { day: 'Tue', workouts: 1, date: '2023-10-02' },
      { day: 'Wed', workouts: 3, date: '2023-10-03' },
      { day: 'Thu', workouts: 0, date: '2023-10-04' },
      { day: 'Fri', workouts: 2, date: '2023-10-05' },
      { day: 'Sat', workouts: 1, date: '2023-10-06' },
      { day: 'Sun', workouts: 0, date: '2023-10-07' }
    ],
    achievements: [
      { id: 1, title: 'First Workout', description: 'Completed your first workout', icon: '🏆' }
    ]
  };

  beforeEach(() => {
    vi.clearAllMocks();
    
    // Mock localStorage
    Storage.prototype.getItem = vi.fn(() => 'fake-token');
    
    // Mock successful API response
    axios.get.mockResolvedValue({ data: mockProgressData });
  });

  const renderWithProviders = () => {
    return render(
      <AuthProvider>
        <BrowserRouter>
          <Profile />
        </BrowserRouter>
      </AuthProvider>
    );
  };

  test('renders profile information correctly', async () => {
    renderWithProviders();

    // Wait for data to load
    await waitFor(() => {
      expect(screen.getByText(/Good morning, John!/i)).toBeInTheDocument();
    });

    // Check if streak information is displayed
    expect(screen.getByText('5 days')).toBeInTheDocument();
    
    // Check if next workout is displayed
    expect(screen.getByText('Full Body Workout')).toBeInTheDocument();
    
    // Check if goal progress is displayed
    expect(screen.getByText('2 of 5 workouts')).toBeInTheDocument();
    
    // Check if chart is rendered
    expect(screen.getByText('Workout Progress')).toBeInTheDocument();
    expect(screen.getByTestId('responsive-container')).toBeInTheDocument();
    
    // Check if achievements section is rendered
    expect(screen.getByTestId('achievements-component')).toBeInTheDocument();
  });

  test('handles API error gracefully', async () => {
    // Mock API failure
    axios.get.mockRejectedValue(new Error('Network error'));
    
    // Spy on console.error
    const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {});

    renderWithProviders();

    // Wait for API call to fail
    await waitFor(() => {
      expect(axios.get).toHaveBeenCalledWith(
        'http://127.0.0.1:8000/api/user_progress',
        expect.any(Object)
      );
    });

    // Check if error was logged
    expect(consoleSpy).toHaveBeenCalled();
    expect(consoleSpy.mock.calls[0][0]).toBe('Error fetching progress data:');
    
    consoleSpy.mockRestore();
  });

  test('displays correct authorization header in API request', async () => {
    // Mocking localStorage.getItem to return a fake token for testing
    global.localStorage.setItem('token', 'fake-token');
  
    renderWithProviders();
  
    // Wait for the API call to complete and check the request
    await waitFor(() => {
      expect(axios.get).toHaveBeenCalledWith(
        'http://127.0.0.1:8000/api/user_progress',
        {
          headers: {
            Authorization: 'Bearer fake-token',  // Check that token is included correctly
          },
          withCredentials: true,
        }
      );
    });
  });  
});
