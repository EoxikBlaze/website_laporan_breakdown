import '../css/app.css';
import './bootstrap';

import { createRoot } from 'react-dom/client';
import { IosDateTimePicker } from './components/ui/ios-datetime-picker';
import { IosSelectPicker } from './components/ui/ios-select-picker';
import { ProjectDataTable } from './components/ui/project-data-table';
import { PremiumDataTable } from './components/ui/premium-data-table';
import { MasterUnitTable } from './components/islands/MasterUnitTable';
import { BreakdownLogTable } from './components/islands/BreakdownLogTable';
import { VendorTable } from './components/islands/VendorTable';
import { LatestReportsTable } from './components/islands/LatestReportsTable';
import { AppSidebar } from './components/islands/AppSidebar';

// Mount all [data-react-component] islands declared in Blade templates
const mountIslands = () => {
    const islands = document.querySelectorAll('[data-react-component]');

    islands.forEach(el => {
        const componentName = el.getAttribute('data-react-component');
        const propsData = el.getAttribute('data-props');
        let props: Record<string, unknown> = {};

        try {
            props = JSON.parse(propsData || '{}');
        } catch (e) {
            console.error('Failed to parse props for island:', componentName, e);
        }

        const root = createRoot(el);

        switch (componentName) {
            case 'IosDateTimePicker':
                root.render(<IosDateTimePicker {...(props as any)} />);
                break;
            case 'IosSelectPicker':
                root.render(<IosSelectPicker {...(props as any)} />);
                break;
            case 'ProjectDataTable':
                root.render(<ProjectDataTable {...(props as any)} visibleColumns={new Set((props as any)['visibleColumns'] || [])} />);
                break;
            case 'PremiumDataTable':
                root.render(<PremiumDataTable {...(props as any)} />);
                break;
            case 'MasterUnitTable':
                root.render(<MasterUnitTable {...(props as any)} />);
                break;
            case 'BreakdownLogTable':
                root.render(<BreakdownLogTable {...(props as any)} />);
                break;
            case 'VendorTable':
                root.render(<VendorTable {...(props as any)} />);
                break;
            case 'LatestReportsTable':
                root.render(<LatestReportsTable {...(props as any)} />);
                break;
            case 'AppSidebar':
                root.render(<AppSidebar {...(props as any)} />);
                break;
            default:
                console.warn('Unknown React island:', componentName);
        }
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountIslands);
} else {
    mountIslands();
}
