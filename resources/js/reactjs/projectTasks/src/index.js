import React, { useEffect, useState } from 'react';

import Header from './components/Header'
import Home from './pages/Home';

export default () => {
    const [page, setPage] = useState('HOME');

    return (
        <div className="container-fluid">
            <div className="header">
                <Header />
            </div>
            <div className="main">
                {page === 'HOME' && <Home />}
            </div>

        </div>
    );
}
