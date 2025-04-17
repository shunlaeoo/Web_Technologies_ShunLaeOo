import { toast } from 'react-toastify';
import { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Header from './Header'

function LoginPage() {

    const navigate = useNavigate();
    const { login } = useAuth();

    const [form, setForm] = useState({
      email: '',
      password: ''
    });
  
    const [error, setError] = useState('');
  
    const handleChange = (e) => {
      setForm({ ...form, [e.target.name]: e.target.value });
    };
  
    const handleSubmit = async (e) => {
      e.preventDefault();
      try {
        const res = await axios.post('http://127.0.0.1:8000/api/login', form, {
          withCredentials: true,
        });
  
        const token = res.data.access_token;
        localStorage.setItem('token', token);
        login();

        toast.success('Login successful! 🎉');
        navigate('/home'); // redirect after login
      } catch (err) {
        setError('Invalid email or password');
        toast.error('Invalid email or password.');
      }
    };

    return (
        <>
            <Header />
            <div className="hero-section login">
                <div className="hero-content px-5">
                    <div className="w-full max-w-lg bg-white pt-6 pb-8 px-8 rounded-lg shadow-md">
                        <img className="w-25 mb-3 mx-auto block" src="/image/Logo21.png" alt="Fit Forward Logo" />
                        <h4 className="text-2xl text-dark-600 font-bold mb-5 text-center">
                            Login
                        </h4>
                        {/* <h2 className="text-3xl primary text-center font-bold mb-6">FIT-FORWARD</h2> */}
                        <form onSubmit={handleSubmit} className="space-y-5">
                            <label className="text-dark-600 font-bold">Email</label>
                            <input
                                name='email'
                                type="email"
                                placeholder="example@gmail.com"
                                onChange={handleChange}
                                className="w-full text-sm px-4 py-2 mt-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300"
                            />
                            <label className="text-dark-600 font-bold">Password</label>
                            <input
                                name='password'
                                type="password"
                                placeholder="Password"
                                onChange={handleChange}
                                className="w-full text-sm px-4 py-2 mt-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300"
                            />
                            <button
                                type="submit"
                                className="btn-primary w-full text-white focus:outline-none focus:border-red-300 font-semibold py-2 px-4 rounded transition duration-200"
                            >
                                Login
                            </button>

                            <p className="font-semibold text-center text-sm">
                                Don't have an account?{' '}
                                <a href="/register" className="primary">
                                    Register
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <footer className="bg-[#111]">
                < div className="text-center text-pink-500 text-xs py-4">
                    &copy; 2025 FIT-FORWARD. All rights reserved.
                </div>
            </footer>
        </>    
    );
}
export default LoginPage;
  