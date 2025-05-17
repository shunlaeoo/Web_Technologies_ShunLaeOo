import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import { vi } from 'vitest';
import LoginPage from './LoginPage';
import { useAuth } from '../context/AuthContext';
import { toast } from 'react-toastify';
import axios from 'axios';

// Mock the navigate function
const mockNavigate = vi.fn();

// Mock dependencies
vi.mock('../context/AuthContext', () => ({
  useAuth: vi.fn()
}));

// Mocking react-router-dom's useNavigate
vi.mock('react-router-dom', async () => {
  const actual = await vi.importActual('react-router-dom');
  return {
    ...actual,
    useNavigate: () => mockNavigate
  };
});

// Mocking axios
vi.mock('axios', () => ({
  default: {
    post: vi.fn()
  },
  post: vi.fn()
}));

vi.mock('react-toastify', () => ({
  toast: {
    success: vi.fn(),
    error: vi.fn()
  }
}));

describe('LoginPage Component', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    
    useAuth.mockReturnValue({
      login: vi.fn()
    });

    // Mock the axios response for a successful login
    axios.post.mockResolvedValue({
      data: {
        access_token: 'fake_token'
      }
    });
  });

  test('renders login form correctly', () => {
    render(
      <BrowserRouter>
        <LoginPage />
      </BrowserRouter>
    );
    
    // Check form elements - avoid using getByText for "Login" since it appears multiple times
    // Instead check for specific elements we know should be there
    expect(screen.getByPlaceholderText(/example@gmail.com/i)).toBeInTheDocument();
    expect(screen.getByPlaceholderText(/password/i)).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /login/i })).toBeInTheDocument();
    expect(screen.getByText(/don't have an account\?/i)).toBeInTheDocument();
    expect(screen.getByText(/register/i)).toBeInTheDocument();
  });

  test('updates form state when inputs change', () => {
    render(
      <BrowserRouter>
        <LoginPage />
      </BrowserRouter>
    );
    
    // Get form inputs using placeholder text
    const emailInput = screen.getByPlaceholderText(/example@gmail.com/i);
    const passwordInput = screen.getByPlaceholderText(/password/i);
    
    // Simulate user input
    fireEvent.change(emailInput, { target: { value: 'test@example.com' } });
    fireEvent.change(passwordInput, { target: { value: 'password123' } });
    
    // Check if inputs have the entered values
    expect(emailInput.value).toBe('test@example.com');
    expect(passwordInput.value).toBe('password123');
  });

  test('logs in successfully and redirects', async () => {
    render(
      <BrowserRouter>
        <LoginPage />
      </BrowserRouter>
    );

    const emailInput = screen.getByPlaceholderText(/example@gmail.com/i);
    const passwordInput = screen.getByPlaceholderText(/password/i);
    const loginButton = screen.getByRole('button', { name: /login/i });

    fireEvent.change(emailInput, { target: { value: 'test@example.com' } });
    fireEvent.change(passwordInput, { target: { value: 'password123' } });
    
    fireEvent.click(loginButton);

    // Wait for async behavior to complete
    await waitFor(() => {
      // Assert that axios.post was called with the correct arguments
      expect(axios.post).toHaveBeenCalledWith('http://127.0.0.1:8000/api/login', {
        email: 'test@example.com',
        password: 'password123'
      }, { withCredentials: true });

      // Assert the success toast
      expect(toast.success).toHaveBeenCalledWith('Login successful! 🎉');
      
      // Assert that login was called
      expect(useAuth().login).toHaveBeenCalled();

      // Assert that the navigation happened
      expect(mockNavigate).toHaveBeenCalledWith('/home');
    });
  });

  test('handles login failure correctly', async () => {
    // Mocking an error response
    axios.post.mockRejectedValueOnce(new Error('Invalid credentials'));

    render(
      <BrowserRouter>
        <LoginPage />
      </BrowserRouter>
    );

    const emailInput = screen.getByPlaceholderText(/example@gmail.com/i);
    const passwordInput = screen.getByPlaceholderText(/password/i);
    const loginButton = screen.getByRole('button', { name: /login/i });

    fireEvent.change(emailInput, { target: { value: 'test@example.com' } });
    fireEvent.change(passwordInput, { target: { value: 'wrongpassword' } });
    
    fireEvent.click(loginButton);

    // Wait for async behavior to complete
    await waitFor(() => {
      // Assert that the error toast was called
      expect(toast.error).toHaveBeenCalledWith('Invalid email or password.');

      // Assert that login was not called
      expect(useAuth().login).not.toHaveBeenCalled();
    });
  });
});
