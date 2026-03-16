import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot, hydrateRoot } from 'react-dom/client';
import { User, History } from "lucide-react";
import { format } from "date-fns";
import { cn } from "@/lib/utils";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const el = document.getElementById('app');
if (el) {
    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.tsx`,
                import.meta.glob('./Pages/**/*.tsx'),
            ),
        setup({ el, App, props }) {
            if (import.meta.env.SSR) {
                hydrateRoot(el, <App {...props} />);
                return;
            }

            createRoot(el).render(<App {...props} />);
        },
        progress: {
            color: '#4B5563',
        },
    });
}

// React Islands Support for Blade Templates
import { IosDateTimePicker } from './Components/ui/ios-datetime-picker';
import { ProjectDataTable } from './Components/ui/project-data-table';
import { PremiumDataTable } from './Components/ui/premium-data-table';
import { MasterUnitTable } from './Components/islands/MasterUnitTable';
import { BreakdownLogTable } from './Components/islands/BreakdownLogTable';
import { VendorTable } from './Components/islands/VendorTable';
import { LatestReportsTable } from './Components/islands/LatestReportsTable';
import { AppSidebar } from './Components/islands/AppSidebar';

const mountIslands = () => {
    const islands = document.querySelectorAll('[data-react-component]');
    
    islands.forEach(el => {
        const componentName = el.getAttribute('data-react-component');
        const propsData = el.getAttribute('data-props');
        let props = {};
        
        try {
            props = JSON.parse(propsData || '{}');
        } catch (e) {
            console.error('Failed to parse props for island:', el, e);
        }

        const root = createRoot(el);

        if (componentName === 'IosDateTimePicker') {
            root.render(<IosDateTimePicker {...(props as any)} />);
        } else if (componentName === 'ProjectDataTable') {
            const visibleColumns = new Set((props as any)['visibleColumns'] || []);
            root.render(<ProjectDataTable {...(props as any)} visibleColumns={visibleColumns} />);
        } else if (componentName === 'PremiumDataTable') {
            root.render(<PremiumDataTable {...(props as any)} />);
        } else if (componentName === 'MasterUnitTable') {
            root.render(<MasterUnitTable {...(props as any)} />);
        } else if (componentName === 'BreakdownLogTable') {
            root.render(<BreakdownLogTable {...(props as any)} />);
        } else if (componentName === 'VendorTable') {
            root.render(<VendorTable {...(props as any)} />);
        } else if (componentName === 'LatestReportsTable') {
            root.render(<LatestReportsTable {...(props as any)} />);
        } else if (componentName === 'AppSidebar') {
            root.render(<AppSidebar {...(props as any)} />);
        }
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountIslands);
} else {
    mountIslands();
}
