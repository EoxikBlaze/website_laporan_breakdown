import * as React from "react";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Button } from "@/components/ui/button";
import { ChevronDown, Search, Check } from "lucide-react";
import { cn } from "@/lib/utils";
import { AnimatePresence, motion } from "framer-motion";

interface Option {
    value: string | number;
    label: string;
    description?: string;
}

interface IosSelectPickerProps {
    name: string;
    options: Option[];
    initialValue?: string | number | null;
    placeholder?: string;
    label?: string;
    id?: string;
    required?: boolean;
}

export function IosSelectPicker({ 
    name, 
    options = [], 
    initialValue, 
    placeholder = "Pilih opsi...", 
    label = "Pilih Data", 
    id: idProp,
    required = false
}: IosSelectPickerProps) {
    const [value, setValue] = React.useState<string | number | undefined>(() => {
        return initialValue !== null && initialValue !== undefined ? initialValue : undefined;
    });
    const [isOpen, setIsOpen] = React.useState(false);
    const [isMobile, setIsMobile] = React.useState(false);
    const [searchQuery, setSearchQuery] = React.useState("");

    const id = idProp || name;

    React.useEffect(() => {
        const checkMobile = () => setIsMobile(window.innerWidth < 640);
        checkMobile();
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    const selectedOption = options.find(opt => String(opt.value) === String(value));
    const displayValue = selectedOption ? selectedOption.label : placeholder;

    const filteredOptions = React.useMemo(() => {
        if (!searchQuery.trim()) return options;
        const query = searchQuery.toLowerCase();
        return options.filter(opt => 
            opt.label.toLowerCase().includes(query) || 
            (opt.description && opt.description.toLowerCase().includes(query))
        );
    }, [options, searchQuery]);

    const handleSelect = (val: string | number) => {
        setValue(val);
        setIsOpen(false);
        setSearchQuery(""); // reset search
        
        // Dispatch custom event in case blade logic needs it (e.g. toggle spare time)
        setTimeout(() => {
            const el = document.getElementById(id);
            if (el) {
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }, 50);
    };

    const PickerContent = ({ mobile = false }: { mobile?: boolean }) => (
        <div className={cn(
            "flex flex-col overflow-hidden bg-neutral-50/50",
            mobile ? "w-full max-h-[85vh] rounded-t-[32px] sm:rounded-[32px]" : "w-[320px] rounded-[24px]"
        )}>
            {/* Header */}
            <div className="px-6 py-5 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white shadow-md flex-shrink-0 relative z-10">
                <div className="flex flex-col">
                    <p className="text-[10px] uppercase tracking-[0.25em] font-black opacity-60 mb-1">Pilihan</p>
                    <span className="text-lg font-bold">{label}</span>
                </div>
            </div>

            {/* Search Bar */}
            <div className="p-3 bg-white border-b border-neutral-100 flex-shrink-0">
                <div className="relative">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-neutral-400" />
                    <input 
                        type="text" 
                        placeholder="Cari data..."
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        className="w-full pl-9 pr-4 py-2 bg-neutral-100/80 border-transparent rounded-xl text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none"
                    />
                </div>
            </div>

            {/* Options List */}
            <div className="flex-1 overflow-y-auto max-h-[400px] p-2 space-y-1">
                {!required && (
                    <button
                        type="button"
                        onClick={() => handleSelect("")}
                        className={cn(
                            "w-full text-left px-4 py-3 rounded-xl text-sm transition-all flex items-center justify-between group",
                            !value 
                                ? "bg-blue-50 text-blue-700 font-bold" 
                                : "hover:bg-neutral-100 text-neutral-600 font-medium"
                        )}
                    >
                        <span>{placeholder} (Kosongkan)</span>
                        {!value && <Check className="h-4 w-4 text-blue-600" />}
                    </button>
                )}
                
                {filteredOptions.length === 0 ? (
                    <div className="py-8 text-center text-sm text-neutral-400">
                        Tidak ada data ditemukan.
                    </div>
                ) : (
                    filteredOptions.map((opt) => {
                        const isSelected = String(opt.value) === String(value);
                        return (
                            <button
                                type="button"
                                key={opt.value}
                                onClick={() => handleSelect(opt.value)}
                                className={cn(
                                    "w-full text-left px-4 py-3 rounded-xl transition-all flex items-center justify-between group",
                                    isSelected 
                                        ? "bg-blue-600 text-white shadow-md shadow-blue-600/20" 
                                        : "hover:bg-neutral-100 bg-white border border-neutral-100/50"
                                )}
                            >
                                <div className="flex flex-col pr-4">
                                    <span className={cn(
                                        "text-sm", 
                                        isSelected ? "font-bold text-white" : "font-bold text-neutral-800"
                                    )}>
                                        {opt.label}
                                    </span>
                                    {opt.description && (
                                        <span className={cn(
                                            "text-[10px] mt-0.5",
                                            isSelected ? "text-blue-100" : "text-neutral-400"
                                        )}>
                                            {opt.description}
                                        </span>
                                    )}
                                </div>
                                {isSelected && <Check className="h-4 w-4 text-white flex-shrink-0" />}
                            </button>
                        );
                    })
                )}
            </div>
        </div>
    );

    return (
        <div className="w-full">
            <input type="hidden" name={name} id={id} value={value ?? ""} />
            
            <Popover open={!isMobile && isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant={"outline"}
                        type="button"
                        onClick={() => isMobile && setIsOpen(!isOpen)}
                        className={cn(
                            "w-full justify-between items-center text-left font-medium h-12 px-4 border-neutral-200 rounded-xl bg-white hover:bg-neutral-50 hover:border-blue-300 transition-all duration-200 shadow-sm",
                            !value && "text-neutral-400"
                        )}
                    >
                        <span className="truncate flex-1 text-sm">{displayValue}</span>
                        <div className="bg-neutral-100/80 p-1.5 rounded-lg text-neutral-500 ml-2">
                            <ChevronDown className="h-4 w-4" />
                        </div>
                    </Button>
                </PopoverTrigger>
                {!isMobile && (
                    <PopoverContent 
                        className="w-[320px] p-0 border border-neutral-200 shadow-2xl rounded-[24px] overflow-hidden bg-white mt-2" 
                        align="start"
                    >
                        <PickerContent />
                    </PopoverContent>
                )}
            </Popover>

            <AnimatePresence>
                {isMobile && isOpen && (
                    <div className="fixed inset-0 z-[100] flex items-end sm:items-center justify-center sm:p-4">
                        <motion.div 
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            onClick={() => setIsOpen(false)}
                            className="absolute inset-0 bg-black/40 backdrop-blur-sm"
                        />
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 100 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 100 }}
                            className="relative z-10 w-full sm:max-w-sm bg-white rounded-t-[32px] sm:rounded-[32px] overflow-hidden shadow-2xl"
                        >
                            <PickerContent mobile />
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>
        </div>
    );
}
