import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg {...props} viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <path
                fillRule="evenodd"
                clipRule="evenodd"
                d="M8 5a5 5 0 0 0-5 5v16a5 5 0 0 0 5 5h1l-3 4h4l3-4h14l3 4h4l-3-4h1a5 5 0 0 0 5-5V10a5 5 0 0 0-5-5H8Zm1 5h9v7H7v-5a2 2 0 0 1 2-2Zm13 0h9a2 2 0 0 1 2 2v5H22v-7ZM7 21h26v4a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2v-4Zm4 1.5a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm18 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"
            />
        </svg>
    );
}
