import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';

const Authors_info = () => {
    const { id } = useParams();
    const [query, setQuery] = useState('id,author_surname,author_name,author_patronymic kursovaya."Authors_catalog"');
    const [authors, setAuthors] = useState([]);
    const [error, setError] = useState('');

    useEffect(() => {
        const fetchData = async () => {
            setError('');
            try {
                const response = await axios.get(`https://kursovaya.local/select.php`, {
                    params: { query },
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                setAuthors(response.data || []);
            } catch (err) {
                setError('Произошла ошибка при запросе.');
                console.error('Ошибка при выполнении запроса:', err.response ? err.response.data : err.message);
            }
        };
        fetchData();
    }, []);

    if (error) {
        return <p>{error}</p>;
    }

    if (!authors || authors.length === 0) {
        return <p>Данные об авторе не найдены.</p>;
    }

    const author = authors.find(b => b.id === id);

    if (!author) {
        return <p>Данные об авторе не найдены.</p>;
    }

    return (
        <>
            <p>{author.author_surname}</p>
            <p>{author.author_name}</p>
            <p>{author.author_patronymic}</p>
        </>
    );
};

export default Authors_info;
