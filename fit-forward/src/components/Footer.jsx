import { Link } from 'react-router-dom';

function Footer() {
    return(
        <>
           <footer className="bg-[#111] text-white">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 px-12 py-5">
                    {/* Branding */}
                    <div>
                        <div className="flex items-center mb-2">
                            <span className="text-xl font-bold text-pink-700">FIT-FORWARD</span>
                        </div>
                        <p className="text-pink-600 text-sm">Empowering your fitness journey.</p>
                    </div>
                    {/* Navigation */}
                    <div>
                        <h4 className="font-semibold text-pink-700 mb-2">Quick Links</h4>
                        <ul className="space-y-2 text-sm">
                            <li><Link to="/home"><p className="text-white hover:underline">Home</p></Link></li>
                            <li><Link to="/profile"><p className="text-white hover:underline">Profile</p></Link></li>
                        </ul>
                    </div>
                </div>
                <div className="text-center text-pink-500 text-xs py-4 border-t border-pink-100">
                    &copy; 2025 FIT-FORWARD. All rights reserved.
                </div>
                </footer>
        </>
    );
}

export default Footer;