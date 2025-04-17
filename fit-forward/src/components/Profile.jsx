import Header from './Header';
import { ResponsiveContainer, LineChart, Line, XAxis, YAxis, CartesianGrid } from 'recharts';
import axios from 'axios';
import { useEffect, useState } from 'react';
import Footer from './Footer';

function Profile() {
    const [progressData, setProgressData] = useState(null);

    useEffect(() => {
        const fetchProgressData = async () => {
          const token = localStorage.getItem('token');
      
          try {
            const res = await axios.get('http://127.0.0.1:8000/api/user_progress', {
              headers: {
                Authorization: `Bearer ${token}`,
              },
              withCredentials: true,
            });
      
            setProgressData(res.data);
          } catch (error) {
            console.error('Error fetching progress data:', error);
          }
        };
        fetchProgressData();
    }, []);
      
    return(
        <>
            <Header />
            <div className="fitness-app bg-gradient-to-br from-pink-50 to-purple-50 p-6 md:p-10">
                <header className="app-header font-bold">
                    <h1>{progressData?.greeting}, {progressData?.user_name}! 
                        <span className="emoji"> 😊</span>
                    </h1>
                </header>

                <div className="dashboard-grid grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    {/* Streak Card */}
                    <div className="dashboard-card streak-card bg-white rounded-lg shadow-sm p-4">
                        <div className="card-header flex items-center justify-between">
                            <h2 className="text-lg font-semibold">Current Streak</h2>
                            <span className="trophy-icon animate-bounce text-2xl">📈</span>
                        </div>
                        <div className="card-content">
                            <h3 className="streak-count text-2xl font-bold">
                                {progressData?.streak === 1 ? "1 day" : `${progressData?.streak} days`}
                            </h3>
                            <p className="streak-message text-sm text-gray-600 mt-1">
                                Keep the momentum going! <span className="star">⭐</span>
                            </p>
                        </div>
                    </div>

                    {/* Next Workout Card */}
                    <div className="dashboard-card workout-card bg-white rounded-lg shadow-sm p-4">
                        <div className="card-header flex items-center justify-between">
                            <h2 className="text-lg font-semibold">Next Workout</h2>
                            <span className="calendar-icon animate-bounce text-2xl">📅</span>
                        </div>
                        <div className="card-content">
                            <h3 className="workout-title text-2xl font-bold">{progressData?.plan}</h3>
                            <p className="workout-schedule text-sm text-gray-600 mt-1">Scheduled for today</p>
                        </div>
                    </div>

                    {/* Daily Goal Card */}
                    <div className="dashboard-card goal-card bg-white rounded-lg shadow-sm p-4">
                        <div className="card-header flex items-center justify-between">
                            <h2 className="text-lg font-semibold">Daily Goal</h2>
                            <span className="heart-icon animate-bounce text-2xl">🏆</span>
                        </div>
                        <div className="card-content">
                            <h3 className="goal-progress text-2xl font-bold mb-3">
                                {progressData?.goal?.completed} of {progressData?.goal?.total} workouts
                            </h3>
                            <div className="w-full h-2 bg-gray-200 rounded-full">
                                <div className="progress h-full bg-pink-400 rounded-full"
                                    style={{ width: `${progressData?.progress || 0}%` }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="detailed-sections">
                    {/* Workout Progress Section */}
                    <div className="section progress-section rounded-lg shadow-sm">
                        <div className="section-header bg-gradient-to-br from-pink-100 to-purple-50 rounded-t-lg py-4 px-6">
                            <h2 className='text-2xl'>
                                <span className="heart-icon text-2xl animate-bounce mt-2">📊</span> 
                                Workout Progress
                            </h2>
                            <span className="time-period">Last 7 days</span>
                        </div>
                        <div className="chart-container rounded-b-lg py-8 pe-10 w-full h-[300px]">
                            <ResponsiveContainer width="100%" height="100%">
                                <LineChart data={progressData?.weeklyData}>
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis dataKey="day" />
                                    <YAxis domain={[0, 4]} ticks={[0, 1, 2, 3, 4, 5]} />
                                    <Line 
                                        type="monotone" 
                                        dataKey="workouts" 
                                        stroke="#ff4d79" 
                                        strokeWidth={2} 
                                        dot={{ r: 4 }} 
                                        activeDot={{ r: 6 }}
                                    />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </div>
            <Footer/>
        </>
    );
}

export default Profile;