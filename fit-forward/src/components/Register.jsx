import { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

function Register() {
    const navigate = useNavigate();
    const [form, setForm] = useState({
        name: '',
        email: '',
        password: '',
        age: '',
        gender: '',
        height: '',
        weight: '',
        activity_level: '',
    });
    const [error, setError] = useState('');

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await axios.post('http://127.0.0.1:8000/api/register', form, {
                withCredentials: true,
            });
            navigate('/login');
        } catch (err) {
            setError('Registration failed. Please check your inputs.');
            console.error(err);
        }
    };
    return(
        <div className="hero-section login py-5">
            <div className="hero-content px-5">
                <div className="w-full max-w-2xl bg-white pt-6 pb-8 px-8 rounded-lg shadow-md">
                    <img className="w-25 mb-2 mx-auto block" src="/image/Logo21.png" alt="Fit Forward Logo" />
                    <h2 className="text-2xl text-dark-600 font-bold mb-5 text-center">
                        Register
                    </h2>
                    {/* <h2 className="text-3xl primary text-center font-bold mb-6">FIT-FORWARD</h2> */}

                    <form onSubmit={handleSubmit} className="space-y-5">
                        <label className="text-dark-600 font-bold">Full Name</label>
                        <input name="name"
                            type='text' 
                            value={form.name} 
                            onChange={handleChange} 
                            placeholder="Full Name" 
                            className="w-full text-sm px-4 py-2 mt-1 mb-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300" />
                        
                        <label className="text-dark-600 font-bold">Email</label>
                        <input name="email" 
                            value={form.email} 
                            onChange={handleChange} 
                            type="email" 
                            placeholder="Email" 
                            className="w-full text-sm px-4 py-2 mt-1 mb-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300" />
                        
                        <label className="text-dark-600 font-bold">Password</label>
                        <input name="password" 
                            value={form.password} 
                            onChange={handleChange} 
                            type="password" 
                            placeholder="Password" 
                            className="w-full text-sm px-4 py-2 mt-1 mb-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300" />
                        
                        <div className='flex gap-x-3 my-0'>
                            <label className="w-1/2 text-dark-600 font-bold">Age</label>
                            <label className="w-1/2 text-dark-600 font-bold">Gender</label>
                        </div>

                        <div className='flex gap-x-3 mb-3'>
                            <input name="age"  
                                onChange={handleChange} 
                                value={form.age}
                                type="number" 
                                min="1"
                                placeholder="Age"
                                className="w-1/2 text-sm px-4 py-2 mt-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300" />
                            
                            <select name="gender" 
                                value={form.gender}
                                onChange={handleChange} 
                                className="w-1/2 text-sm px-4 py-2 mt-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300">
                                <option value="" disabled>Select Gender</option>
                                <option value="0">Female</option>
                                <option value="1">Male</option>
                            </select>
                        </div>

                        <div className='flex gap-x-3 my-0'>
                            <label className="w-1/2 text-dark-600 font-bold">Height</label>
                            <label className="w-1/2 text-dark-600 font-bold">Weight</label>
                        </div>

                        <div className='flex gap-x-3 mb-3'>
                            <input name="height" 
                                value={form.height}
                                onChange={handleChange} 
                                type="number" 
                                placeholder="Height (cm)"
                                min="1" 
                                className="w-1/2 text-sm px-4 py-2 mt-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300" />
                            <input name="weight" 
                                value={form.weight}
                                onChange={handleChange} 
                                type="number" 
                                min="1"
                                placeholder="Weight (kg)" 
                                className="w-1/2 text-sm px-4 py-2 mt-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300" />
                        </div>

                        <label className="w-1/2 text-dark-600 font-bold">Activity Level</label>
                        <select name="activity_level" 
                            value={form.activity_level}
                            onChange={handleChange} 
                            className="w-full text-sm px-4 py-2 mt-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-300">
                            <option value="" disabled>Activity Level</option>
                            <option value="1">Sedentary (Little/No exercise)</option>
                            <option value="2">Lightly active (1–3 days/week)</option>
                            <option value="3">Moderately active (3–5 days/week)</option>
                            <option value="4">Very active (6–7 days/week)</option>
                            <option value="5">Extra active (Athlete level)</option>
                        </select>

                        <button type="submit" className="btn-primary w-full bg-red-500 text-white font-semibold py-2 px-4 rounded hover:bg-red-600 transition duration-200">
                            Register
                        </button>

                        {error && <p className="text-red-500 text-center">{error}</p>}

                        <p className="font-semibold text-center text-sm">
                            Already have an account?{' '}
                            <a href="/login" className="primary font-bold">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    );
}
export default Register;