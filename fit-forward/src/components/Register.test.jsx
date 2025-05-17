import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { vi } from 'vitest';
import Register from './Register';
import { useAuth } from '../context/AuthContext';
import axios from 'axios';
import { toast } from 'react-toastify';
import { BrowserRouter } from 'react-router-dom';

// Mock dependencies
vi.mock('axios');
vi.mock('react-toastify', () => ({
  toast: {
    success: vi.fn(),
    error: vi.fn(),
  }
}));

vi.mock('../context/AuthContext', () => ({
  useAuth: vi.fn(),
}));

// Mock useNavigate
const mockNavigate = vi.fn();
vi.mock('react-router-dom', async () => {
  const actual = await vi.importActual('react-router-dom');
  return {
    ...actual,
    useNavigate: () => mockNavigate,
  };
});

describe('Register Component', () => {
  const mockLogin = vi.fn();

  beforeEach(() => {
    vi.clearAllMocks();

    useAuth.mockReturnValue({
      login: mockLogin,
    });

    // Mock localStorage methods
    Storage.prototype.getItem = vi.fn();
    Storage.prototype.setItem = vi.fn();
  });

  test('renders registration form correctly', () => {
    render(
      <BrowserRouter>
        <Register />
      </BrowserRouter>
    );
  
    // Check if all necessary form fields are rendered
    expect(screen.getByRole('heading', { name: 'Register' })).toBeInTheDocument();
    expect(screen.getByLabelText('Full Name')).toBeInTheDocument();
    expect(screen.getByLabelText('Email')).toBeInTheDocument();
    expect(screen.getByLabelText('Password')).toBeInTheDocument();
    expect(screen.getByLabelText('Age')).toBeInTheDocument();
    expect(screen.getByLabelText('Gender')).toBeInTheDocument();
    expect(screen.getByLabelText('Height')).toBeInTheDocument();
    expect(screen.getByLabelText('Weight')).toBeInTheDocument();
  
    // Ensure only the label for activity level is checked 
    const activityLevelLabel = screen.queryAllByText('Activity Level');
    expect(activityLevelLabel).toHaveLength(2);
  
    expect(screen.getByRole('button', { name: 'Register' })).toBeInTheDocument();
    expect(screen.getByText('Already have an account?')).toBeInTheDocument();
    expect(screen.getByText('Login')).toBeInTheDocument();
  });
    

  test('updates form state when inputs change', () => {
    render(
      <BrowserRouter>
        <Register />
      </BrowserRouter>
    );

    // Get form inputs by placeholder or name attribute
    const nameInput = screen.getByPlaceholderText('John Doe');
    const emailInput = screen.getByPlaceholderText('example@gmail.com');
    const passwordInput = screen.getByPlaceholderText('Password');

    // Simulate user input
    fireEvent.change(nameInput, { target: { value: 'John Doe', name: 'name' } });
    fireEvent.change(emailInput, { target: { value: 'john@example.com', name: 'email' } });
    fireEvent.change(passwordInput, { target: { value: 'password123', name: 'password' } });

    // Check if inputs have the entered values
    expect(nameInput.value).toBe('John Doe');
    expect(emailInput.value).toBe('john@example.com');
    expect(passwordInput.value).toBe('password123');
  });

  test('handles successful registration and auto-login', async () => {
    // Mock successful API responses
    axios.post.mockImplementation((url) => {
      if (url === 'http://127.0.0.1:8000/api/register') {
        return Promise.resolve({ data: { success: true } });
      } else if (url === 'http://127.0.0.1:8000/api/login') {
        return Promise.resolve({ data: { access_token: 'fake-token' } });
      }
      return Promise.reject(new Error('Unknown URL'));
    });
  
    // Mock login from useAuth context
    const mockLogin = vi.fn();
    useAuth.mockReturnValue({
      login: mockLogin
    });
  
    // Mock localStorage
    Storage.prototype.setItem = vi.fn();
  
    render(
      <BrowserRouter>
        <Register />
      </BrowserRouter>
    );
  
    // Fill form with minimum required fields using placeholders
    fireEvent.change(screen.getByPlaceholderText('John Doe'), { 
      target: { value: 'John Doe', name: 'name' } 
    });
    fireEvent.change(screen.getByPlaceholderText('example@gmail.com'), { 
      target: { value: 'john@example.com', name: 'email' } 
    });
    fireEvent.change(screen.getByPlaceholderText('Password'), { 
      target: { value: 'password123', name: 'password' } 
    });
  
    // For numeric inputs, use more specific selectors
    fireEvent.change(screen.getByPlaceholderText('30'), { 
      target: { value: '30', name: 'age' } 
    });
  
    // For select inputs, find them by their test id or directly by role
    const genderSelect = screen.getAllByRole('combobox')[0]; // First dropdown is gender
    fireEvent.change(genderSelect, { target: { value: '1', name: 'gender' } });
  
    fireEvent.change(screen.getByPlaceholderText('175 (cm)'), { 
      target: { value: '175', name: 'height' } 
    });
    fireEvent.change(screen.getByPlaceholderText('70 (kg)'), { 
      target: { value: '70', name: 'weight' } 
    });
  
    const activitySelect = screen.getAllByRole('combobox')[1]; // Second dropdown is activity level
    fireEvent.change(activitySelect, { target: { value: '3', name: 'activity_level' } });
  
    // Submit form
    fireEvent.submit(screen.getByRole('button', { name: 'Register' }));
  
    // Wait for async operations
    await waitFor(() => {
      // Check if register API was called
      expect(axios.post).toHaveBeenCalledWith(
        'http://127.0.0.1:8000/api/register',
        expect.objectContaining({
          name: 'John Doe',
          email: 'john@example.com',
          password: 'password123',
          age: '30',
          gender: '1',
          height: '175',
          weight: '70',
          activity_level: '3',
        }),
        { withCredentials: true }
      );
  
      // Check if login API was called
      expect(axios.post).toHaveBeenCalledWith(
        'http://127.0.0.1:8000/api/login',
        {
          email: 'john@example.com',
          password: 'password123',
        },
        { withCredentials: true }
      );
  
      // Check if login function was called
      expect(mockLogin).toHaveBeenCalled();
  
      // Check if toast success was called
      expect(toast.success).toHaveBeenCalledWith('Login successful! 🎉');
  
      // Check if navigation occurred
      expect(mockNavigate).toHaveBeenCalledWith('/home', expect.any(Object));
    });
  });  

  test('handles registration failure', async () => {
    // Mock failed API response with validation errors
    axios.post.mockRejectedValueOnce({
      response: {
        status: 422,
        data: {
          email: ['The email has already been taken.'],
        },
      },
    });

    render(
      <BrowserRouter>
        <Register />
      </BrowserRouter>
    );

    // Fill form with minimum required fields using placeholders
    fireEvent.change(screen.getByPlaceholderText('John Doe'), { 
      target: { value: 'John Doe', name: 'name' } 
    });
    fireEvent.change(screen.getByPlaceholderText('example@gmail.com'), { 
      target: { value: 'existing@example.com', name: 'email' } 
    });
    fireEvent.change(screen.getByPlaceholderText('Password'), { 
      target: { value: 'password123', name: 'password' } 
    });
    
    // For numeric inputs, use more specific selectors
    const ageInput = screen.getByPlaceholderText('30');
    fireEvent.change(ageInput, { target: { value: '30', name: 'age' } });
    
    // For select inputs, find them by their test id or directly by role
    const genderSelect = screen.getAllByRole('combobox')[0]; // First dropdown is gender
    fireEvent.change(genderSelect, { target: { value: '1', name: 'gender' } });
    
    fireEvent.change(screen.getByPlaceholderText('175 (cm)'), { 
      target: { value: '175', name: 'height' } 
    });
    fireEvent.change(screen.getByPlaceholderText('70 (kg)'), { 
      target: { value: '70', name: 'weight' } 
    });
    
    const activitySelect = screen.getAllByRole('combobox')[1]; // Second dropdown is activity level
    fireEvent.change(activitySelect, { target: { value: '3', name: 'activity_level' } });

    // Submit form
    fireEvent.submit(screen.getByRole('button', { name: 'Register' }));

    // Wait for async operations
    await waitFor(() => {
      // Check if toast.error was called with the validation error
      expect(toast.error).toHaveBeenCalledWith('The email has already been taken.');

      // Check that login was not called
      expect(mockLogin).not.toHaveBeenCalled();

      // Check that navigation did not occur
      expect(mockNavigate).not.toHaveBeenCalled();
    });
  });
});
