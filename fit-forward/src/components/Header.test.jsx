import { render, screen, fireEvent } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import { vi } from 'vitest';
import Header from './Header';
import { useAuth } from '../context/AuthContext';

// Mock the useAuth hook
vi.mock('../context/AuthContext', () => ({
  useAuth: vi.fn()
}));

// Mock the useNavigate hook
vi.mock('react-router-dom', async () => {
  const actual = await vi.importActual('react-router-dom');
  return {
    ...actual,
    useNavigate: () => vi.fn()
  };
});

// Mock react-toastify
vi.mock('react-toastify', () => ({
  toast: {
    success: vi.fn()
  }
}));

describe('Header Component', () => {
  test('renders logo correctly', () => {
    // Mock auth context for unauthenticated user
    useAuth.mockReturnValue({
      isAuthenticated: false,
      logout: vi.fn()
    });

    render(
      <BrowserRouter>
        <Header />
      </BrowserRouter>
    );
    
    // Check if logo is rendered
    const logo = screen.getByAltText('Fit Forward Logo');
    expect(logo).toBeInTheDocument();
    expect(logo.src).toContain('/image/landscape.png');
  });

  test('shows GET STARTED button when not authenticated', () => {
    // Mock auth context for unauthenticated user
    useAuth.mockReturnValue({
      isAuthenticated: false,
      logout: vi.fn()
    });

    render(
      <BrowserRouter>
        <Header />
      </BrowserRouter>
    );
    
    // Check if GET STARTED button is rendered
    const getStartedButton = screen.getByText('GET STARTED →');
    expect(getStartedButton).toBeInTheDocument();
    expect(getStartedButton.closest('a')).toHaveAttribute('href', '/login');
  });

  test('shows Profile and Logout buttons when authenticated', () => {
    // Mock auth context for authenticated user
    const mockLogout = vi.fn();
    useAuth.mockReturnValue({
      isAuthenticated: true,
      logout: mockLogout
    });

    render(
      <BrowserRouter>
        <Header />
      </BrowserRouter>
    );
    
    // Check if Profile button is rendered
    const profileButton = screen.getByText('Profile');
    expect(profileButton).toBeInTheDocument();
    
    // Check if Logout button is rendered
    const logoutButton = screen.getByText('Logout');
    expect(logoutButton).toBeInTheDocument();
  });

  test('calls logout function when Logout button is clicked', () => {
    // Mock auth context for authenticated user
    const mockLogout = vi.fn();
    useAuth.mockReturnValue({
      isAuthenticated: true,
      logout: mockLogout
    });

    render(
      <BrowserRouter>
        <Header />
      </BrowserRouter>
    );
    
    // Click the Logout button
    const logoutButton = screen.getByText('Logout');
    fireEvent.click(logoutButton);
    
    // Check if logout function was called
    expect(mockLogout).toHaveBeenCalled();
  });
});